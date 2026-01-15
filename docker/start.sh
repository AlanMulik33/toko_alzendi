#!/bin/sh
set -euo pipefail

echo "Starting Toko Alzendi application..."

# 1) Create .env from available sources if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
  echo "Creating .env..."
  if [ -f /var/www/html/.env.railway ]; then
    cp /var/www/html/.env.railway /var/www/html/.env
    echo "Copied .env.railway -> .env"
  elif [ -f /var/www/html/.env.example ]; then
    cp /var/www/html/.env.example /var/www/html/.env
    echo "Copied .env.example -> .env"
  else
    echo "No .env.example or .env.railway found. Creating minimal .env from env vars..."
    cat > /var/www/html/.env <<EOF
APP_NAME="${APP_NAME:-Toko Alzendi}"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}
DB_CONNECTION=${DB_CONNECTION:-sqlite}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-database}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-}
EOF
  fi
fi

# 2) Ensure ownership & dirs
mkdir -p /var/run/php-fpm /var/www/html/storage/logs /var/www/html/bootstrap/cache /var/run/nginx
chown -R www-data:www-data /var/www/html /var/run/php-fpm /var/run/nginx || true
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache || true

# 3) Generate APP_KEY if missing in .env
if ! grep -q "^APP_KEY=" /var/www/html/.env 2>/dev/null || grep -q "^APP_KEY=$" /var/www/html/.env 2>/dev/null; then
  echo "Generating APP_KEY..."
  cd /var/www/html
  php artisan key:generate --force || echo "Warning: Could not generate APP_KEY"
fi

# 4) Run migrations (best-effort)
echo "Running migrations..."
cd /var/www/html
php artisan migrate --force 2>&1 || echo "Warning: Migrations failed or nothing to run"

# 5) Start supervisord (which starts php-fpm and nginx)
echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf