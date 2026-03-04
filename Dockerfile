FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
        intl \
        gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-interaction

# Set correct permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose Railway port
EXPOSE 8000

# Start Laravel
CMD php artisan config:cache
CMD php artisan serve --host=0.0.0.0 --port=${PORT}