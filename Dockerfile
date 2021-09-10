FROM php:8.0-cli-alpine

LABEL maintainer="Oleg Tikhonov <to@toro.one>"

# Dependencies

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin

RUN install-php-extensions \
    soap \
    xdebug-3.0.4

# Composer

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --2

# Project

WORKDIR /usr/app

COPY composer.json composer.lock ./

RUN composer validate --strict \
    && composer check-platform-reqs

COPY Makefile phpcs.xml.dist phpunit.xml.dist ./
COPY ./src ./src
COPY ./tests ./tests
