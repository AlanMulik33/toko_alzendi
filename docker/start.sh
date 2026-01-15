#!/bin/sh
set -e

echo "Starting Toko Alzendi application..."

# Copy .env from .env.example if .env doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.example..."
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
        echo ".env file created successfully"
    else
        echo "ERROR: .env.example not found!"
        exit 1
    fi
fi

# Generate APP_KEY if not already set
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating APP_KEY..."
    cd /var/www/html
    php artisan key:generate 2>&1 || true
fi

# Create necessary directories
mkdir -p /var/run/php-fpm /var/log/supervisor /var/www/html/storage/logs
chown -R www-data:www-data /var/run/php-fpm /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations
echo "Running migrations..."
cd /var/www/html
php artisan migrate --force 2>&1 || echo "Migrations completed with status: $?"

# Cache config and routes for production
echo "Caching configuration..."
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
