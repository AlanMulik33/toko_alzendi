#!/bin/sh
set -euo pipefail

if [ -z "$PORT" ]; then
  echo "PORT not set by Railway"
  exit 1
fi

echo "Starting Toko Alzendi - using PORT=${PORT}"

# 1) Generate nginx.conf from template: replace LISTEN_PORT with actual $PORT
NGINX_TEMPLATE="/var/www/html/docker/nginx.conf"

if [ ! -f "$NGINX_TEMPLATE" ]; then
  echo "ERROR: nginx template not found at $NGINX_TEMPLATE"
  exit 1
fi

echo "Generating /etc/nginx/nginx.conf from template (PORT=${PORT})..."
sed "s/LISTEN_PORT/${PORT}/g" "$NGINX_TEMPLATE" > /etc/nginx/nginx.conf


# 2) Ensure directories & ownership
mkdir -p /var/run/php-fpm /var/www/html/storage/logs /var/www/html/bootstrap/cache /var/run/nginx
chown -R www-data:www-data /var/www/html /var/run/php-fpm /var/run/nginx || true
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache || true

# 3) Create .env from environment values (so DB creds are actual values, not placeholders)
if [ ! -f /var/www/html/.env ]; then
  echo "Creating .env from environment variables..."
  cat > /var/www/html/.env <<EOF
APP_NAME="${APP_NAME:-Toko Alzendi}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="${RAILWAY_MYSQL_HOST:-${DB_HOST:-127.0.0.1}}"
DB_PORT="${RAILWAY_MYSQL_PORT:-${DB_PORT:-3306}}"
DB_DATABASE="${RAILWAY_MYSQL_DB:-${DB_DATABASE:-database}}"
DB_USERNAME="${RAILWAY_MYSQL_USER:-${DB_USERNAME:-root}}"
DB_PASSWORD="${RAILWAY_MYSQL_PASSWORD:-${DB_PASSWORD:-}}"

# (you can add other env keys here as needed)
EOF
  echo ".env created"
else
  echo ".env already exists, skipping creation"
fi

# 4) Generate APP_KEY if empty
if ! grep -q "^APP_KEY=" /var/www/html/.env 2>/dev/null || grep -q "^APP_KEY=$" /var/www/html/.env 2>/dev/null; then
  echo "Generating APP_KEY..."
  cd /var/www/html
  php artisan key:generate --force || echo "Warning: Could not generate APP_KEY"
fi

# 5) Run migrations (best-effort)
echo "Running migrations..."
php artisan migrate --force || true

echo "Running seeders..."
php artisan db:seed --force || true

php artisan optimize:clear
php artisan optimize
php artisan config:clear
php artisan route:clear
php artisan view:clear


# 6) Start supervisord (php-fpm + nginx)
mkdir -p /var/lib/nginx/tmp /var/lib/nginx/tmp/fastcgi
chown -R www-data:www-data /var/lib/nginx
chmod -R 755 /var/lib/nginx
echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf