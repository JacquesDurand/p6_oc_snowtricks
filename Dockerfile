ARG PHP_VERSION=8.0
ARG COMPOSER_VERSION=2

FROM composer:${COMPOSER_VERSION} AS composer


FROM php:${PHP_VERSION}-fpm-alpine

RUN set -eux \
    && apk update --no-cache \
    && apk upgrade --no-cache \
    && apk add --no-cache \
        autoconf \
        g++ \
        make \
        postgresql-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql

COPY . /var/www/snow
WORKDIR /var/www/snow

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-interaction

CMD ["php-fpm"]