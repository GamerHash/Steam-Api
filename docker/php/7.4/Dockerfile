FROM php:7.4-alpine

# Install deps
RUN apk --update add bash autoconf build-base wget curl git zip unzip zlib-dev shadow libpq

# Install PHP extensions
RUN docker-php-ext-install exif pcntl bcmath

RUN pecl install xdebug-3.1.5 \
    && docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer:2.2.22 /usr/bin/composer /usr/bin/composer

# Set Working Dir
WORKDIR /srv/app