#!/bin/sh
set -e

echo "Starting Toko Alzendi application..."

# Create . env if not exists
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env. example /var/www/html/. env 2>/dev/null || echo "Warning: . env. example not found"
fi

# Generate APP_KEY if not set
if !  grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating APP_KEY..."
    cd /var/www/html
    php artisan key:generate 2>&1 || echo "Warning: Could not generate APP_KEY"
fi

# Run migrations
echo "Running migrations..."
cd /var/www/html
php artisan migrate --force 2>&1 || echo "Warning:  Migrations might have failed"

# Create necessary directories
mkdir -p /var/run/php-fpm
chown -R www-data:www-data /var/run/php-fpm

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf