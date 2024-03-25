FROM php:7.4-fpm-alpine AS builder

RUN docker-php-ext-install sockets \
        pdo \
        pdo_mysql \
        mysqli

FROM composer:2 AS composer

FROM builder AS workspace
WORKDIR /var/www
COPY --from=composer /usr/bin/composer /usr/bin/composer
