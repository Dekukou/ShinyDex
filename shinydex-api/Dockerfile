FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zip

RUN docker-php-ext-install \
    intl \
    pdo \
    pdo_mysql \
    zip

RUN mkdir -p /var/www/html/var \
    && chown -R www-data:www-data /var/www/html/var

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
