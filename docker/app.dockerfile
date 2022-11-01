FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libgmp-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql gmp

WORKDIR /var/www/html

# Get Composer 2.3.5 (LTS at 04/05/22)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.3.5

COPY . ./

RUN composer install

RUN chown -R www-data storage/
