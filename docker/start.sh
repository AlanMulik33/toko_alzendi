#!/bin/sh
set -e

echo "Starting application..."

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
