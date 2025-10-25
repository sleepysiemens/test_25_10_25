FROM php:8.3-fpm-alpine

WORKDIR /var/www/laravel

RUN apk add --no-cache postgresql-dev && \
        docker-php-ext-install pdo pdo_pgsql

RUN apk add --no-cache nodejs npm yarn