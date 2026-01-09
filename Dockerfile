# Gunakan image PHP 8.4
FROM php:8.4-fpm

# Instal ekstensi yang dibutuhkan
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pdo pdo_mysql zip

# Set working directory
WORKDIR /var/www/html

# Copy semua file
COPY . .

# Instal composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader

# Generate app key dan link storage
RUN php artisan key:generate \
 && php artisan storage:link

# Expose PORT dari Railway
EXPOSE $PORT

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=$PORT"]
