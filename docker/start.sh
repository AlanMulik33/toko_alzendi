#!/bin/sh
set -euo pipefail

echo "Starting Toko Alzendi application..."

# 1) Ensure directories & ownership first
mkdir -p /var/run/php-fpm /var/www/html/storage/logs /var/www/html/bootstrap/cache /var/run/nginx
chown -R www-data:www-data /var/www/html /var/run/php-fpm /var/run/nginx || true
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache || true

# 2) Create .env by writing values from environment (so placeholders are resolved)
if [ ! -f /var/www/html/.env ]; then
  echo "Creating .env from environment variables..."
  cat > /var/www/html/.env <<EOF
APP_NAME="${APP_NAME:-Toko Alzendi}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

APP_LOCALE="${APP_LOCALE:-id}"
APP_FALLBACK_LOCALE="${APP_FALLBACK_LOCALE:-id}"
APP_FAKER_LOCALE="${APP_FAKER_LOCALE:-id_ID}"

BCRYPT_ROUNDS="${BCRYPT_ROUNDS:-12}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"
LOG_STACK="${LOG_STACK:-single}"
LOG_DEPRECATIONS_CHANNEL="${LOG_DEPRECATIONS_CHANNEL:-null}"
LOG_LEVEL="${LOG_LEVEL:-info}"

DB_CONNECTION="${DB_CONNECTION:-mysql}"
# prefer Railway-provided vars, fallback to DB_HOST/DB_USERNAME/etc if set
DB_HOST="${RAILWAY_MYSQL_HOST:-${DB_HOST:-127.0.0.1}}"
DB_PORT="${RAILWAY_MYSQL_PORT:-${DB_PORT:-3306}}"
DB_DATABASE="${RAILWAY_MYSQL_DB:-${DB_DATABASE:-database}}"
DB_USERNAME="${RAILWAY_MYSQL_USER:-${DB_USERNAME:-root}}"
DB_PASSWORD="${RAILWAY_MYSQL_PASSWORD:-${DB_PASSWORD:-}}"

SESSION_DRIVER="${SESSION_DRIVER:-database}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"
SESSION_ENCRYPT="${SESSION_ENCRYPT:-false}"
SESSION_PATH="${SESSION_PATH:-/}"
SESSION_DOMAIN="${SESSION_DOMAIN:-null}"

BROADCAST_CONNECTION="${BROADCAST_CONNECTION:-log}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"

CACHE_STORE="${CACHE_STORE:-database}"

MEMCACHED_HOST="${MEMCACHED_HOST:-127.0.0.1}"

REDIS_CLIENT="${REDIS_CLIENT:-phpredis}"
REDIS_HOST="${REDIS_HOST:-127.0.0.1}"
REDIS_PASSWORD="${REDIS_PASSWORD:-null}"
REDIS_PORT="${REDIS_PORT:-6379}"

MAIL_MAILER="${MAIL_MAILER:-log}"
MAIL_SCHEME="${MAIL_SCHEME:-null}"
MAIL_HOST="${MAIL_HOST:-127.0.0.1}"
MAIL_PORT="${MAIL_PORT:-2525}"
MAIL_USERNAME="${MAIL_USERNAME:-null}"
MAIL_PASSWORD="${MAIL_PASSWORD:-null}"
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-hello@example.com}"
MAIL_FROM_NAME="${MAIL_FROM_NAME:-${APP_NAME}}"

AWS_ACCESS_KEY_ID="${AWS_ACCESS_KEY_ID:-}"
AWS_SECRET_ACCESS_KEY="${AWS_SECRET_ACCESS_KEY:-}"
AWS_DEFAULT_REGION="${AWS_DEFAULT_REGION:-us-east-1}"
AWS_BUCKET="${AWS_BUCKET:-}"
AWS_USE_PATH_STYLE_ENDPOINT="${AWS_USE_PATH_STYLE_ENDPOINT:-false}"

VITE_APP_NAME="${VITE_APP_NAME:-${APP_NAME}}"
EOF

  echo ".env created"
else
  echo ".env already exists, skipping creation"
fi

# 3) Generate APP_KEY if missing
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env 2>/dev/null || grep -q "^APP_KEY=$" /var/www/html/.env 2>/dev/null; then
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