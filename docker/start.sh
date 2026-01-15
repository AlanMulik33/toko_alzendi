#!/bin/sh
set -e

# Create SQLite database if needed (aman walau pakai MySQL)
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite || true
fi

# Start supervisor (nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisord.conf
