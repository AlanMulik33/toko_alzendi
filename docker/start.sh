#!/bin/sh
set -e

echo "Starting application..."

# Create .env if not exists
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate APP_KEY if not set
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating APP_KEY..."
    cd /var/www/html
    php artisan key:generate || echo "Warning: Could not generate APP_KEY"
fi

# Fix permissions
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache
find /var/www/html/storage -type f -exec chmod 644 {} \;
find /var/www/html/bootstrap/cache -type f -exec chmod 644 {} \;

# Create storage directories
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage/logs
chmod 755 /var/www/html/storage/logs

# Run migrations
echo "Running migrations..."
cd /var/www/html
php artisan migrate --force 2>&1 || echo "Warning: Migrations failed"

# Create php-fpm socket directory (CRITICAL)
mkdir -p /var/run/php-fpm
chown -R www-data:www-data /var/run/php-fpm

# Create SQLite database if needed
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
    chmod 666 /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
fi

exec /usr/bin/supervisord -c /etc/supervisord.conf
