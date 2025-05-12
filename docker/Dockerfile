FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    gnupg \
    ca-certificates \
    && pecl install redis \
    && docker-php-ext-install pdo_mysql mbstring zip pcntl \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN usermod -u ${USER_ID} www-data && \
    groupmod -g ${GROUP_ID} www-data

WORKDIR /var/www/html
