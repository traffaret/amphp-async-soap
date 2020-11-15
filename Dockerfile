FROM php:7.3-cli-alpine

LABEL maintainer="Oleg Tikhonov <to@toro.one>"

ARG composer_cache="/usr/.composer/cache"

# Dependencies

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin

RUN install-php-extensions \
    soap \
    xdebug

# Composer

ENV COMPOSER_CACHE_DIR=$composer_cache
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN mkdir -p $COMPOSER_CACHE_DIR

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Project

WORKDIR /usr/app

COPY composer.json composer.lock ./

RUN composer check-platform-reqs \
    && composer install

COPY Makefile phpcs.xml.dist phpunit.xml.dist ./
COPY ./src ./src
COPY ./tests ./tests
