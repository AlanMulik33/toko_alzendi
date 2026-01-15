#!/bin/sh
set -e

echo "=== STARTUP DIAGNOSTIC ==="
echo "TIME: $(date -u +%Y-%m-%dT%H:%M:%SZ)"
echo "ENV PORT: ${PORT:-<not set>}"
echo "---- /etc/nginx/nginx.conf (before) ----"
cat /etc/nginx/nginx.conf || true
echo "----------------------------------------"

if command -v envsubst >/dev/null 2>&1; then
  echo "Running envsubst for nginx.conf"
  envsubst '$PORT' < /etc/nginx/nginx.conf > /etc/nginx/nginx.conf.tmp || true
  mv /etc/nginx/nginx.conf.tmp /etc/nginx/nginx.conf
else
  echo "WARN: envsubst not found!"
fi

echo "---- /etc/nginx/nginx.conf (after) ----"
cat /etc/nginx/nginx.conf || true
echo "----------------------------------------"

echo "---- ps aux ----"
ps aux || true
echo "---- ss -ltnp (listening tcp) ----"
ss -ltnp || true
echo "---- netstat -ltnp (if available) ----"
netstat -ltnp 2>/dev/null || true

echo "---- tail /var/log/nginx/error.log ----"
tail -n 200 /var/log/nginx/error.log || true
echo "----------------------------------------"

if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Creating sqlite database file"
    touch /var/www/html/database/database.sqlite || true
    chmod 666 /var/www/html/database/database.sqlite || true
    chown www-data:www-data /var/www/html/database/database.sqlite || true
fi

# start supervisor which should run php-fpm + nginx
exec /usr/bin/supervisord -c /etc/supervisord.conf