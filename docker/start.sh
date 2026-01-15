#!/bin/sh
set -euo pipefail

PORT="${PORT:-8080}"
echo "Starting Toko Alzendi - using PORT=${PORT}"

# 1) Generate nginx.conf from template: replace LISTEN_PORT with actual $PORT
if [ -f /var/www/html/docker/nginx.conf ]; then
  echo "Generating /etc/nginx/nginx.conf from template (PORT=${PORT})..."
  sed "s/LISTEN_PORT/${PORT}/g" /var/www/html/docker/nginx.conf > /etc/nginx/nginx.conf
else
  echo "ERROR: docker/nginx.conf template not found!"
  exit 1
fi

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
cd /var/www/html
php artisan migrate --force 2>&1 || echo "Warning: Migrations failed or nothing to run"

# 6) Start supervisord (php-fpm + nginx)
echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf