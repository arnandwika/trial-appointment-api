FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-install gd zip

WORKDIR /var/www

COPY . .

RUN chown -R www-data:www-data /var/www