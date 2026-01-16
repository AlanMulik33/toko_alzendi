FROM php:8.4-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    nodejs \
    npm \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    gettext

# Install PHP extensions
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install gd zip mbstring pdo pdo_mysql opcache

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY .  . 

# Install dependencies
RUN composer install --no-dev --optimize-autoloader && \
    npm install && \
    npm run build

# Copy config files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/start.sh /start.sh

# Setup permissions
RUN mkdir -p /var/log/supervisor /var/run/php-fpm /var/www/html/storage/logs && \
    chown -R www-data:www-data /var/www/html /var/log/supervisor /var/run/php-fpm && \
    chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod +x /start.sh

EXPOSE ${PORT}

CMD ["/start.sh"]