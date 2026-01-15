#!/bin/sh
set -e

echo "Starting application..."

# Create .env if not exists
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate APP_KEY if not set
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    cd /var/www/html
    php artisan key:generate
fi

# Run migrations
cd /var/www/html
php artisan migrate --force

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
