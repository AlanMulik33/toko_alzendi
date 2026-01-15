#!/bin/sh
set -e

echo "Starting Toko Alzendi application..."

# Create . env from .env.example if not exists
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.example..."
    if [ -f /var/www/html/.env. example ]; then
        cp /var/www/html/.env. example /var/www/html/. env
    else
        echo "ERROR: .env. example not found!"
        exit 1
    fi
fi

# Generate APP_KEY if not set
if !  grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating APP_KEY..."
    cd /var/www/html
    php artisan key:generate || echo "Warning: Could not generate APP_KEY"
fi

# Create necessary directories
echo "Creating directories..."
mkdir -p /var/run/php-fpm /var/www/html/storage/logs /var/www/html/bootstrap/cache
chown -R www-data: www-data /var/www/html /var/run/php-fpm
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations
echo "Running migrations..."
cd /var/www/html
php artisan migrate --force 2>&1 || echo "Warning:  Migrations may have failed or already ran"

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf