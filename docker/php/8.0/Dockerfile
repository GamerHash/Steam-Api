FROM php:8.0-alpine

# Install deps
RUN apk add --update linux-headers
RUN apk --update add bash autoconf build-base wget curl git zip unzip zlib-dev shadow libpq

# Install PHP extensions
RUN docker-php-ext-install exif pcntl bcmath

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer:2.2.22 /usr/bin/composer /usr/bin/composer

# Set Working Dir
WORKDIR /srv/app