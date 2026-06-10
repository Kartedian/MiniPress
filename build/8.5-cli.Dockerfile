# Use an official PHP runtime as a base image
FROM php:8.5-cli-alpine

# basic update
RUN apk add --no-cache openssl

# installing the docker php extensions installer
RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions

# PHP Configuration
RUN install-php-extensions gettext iconv intl tidy zip sockets \
    pgsql mysqli pdo_mysql pdo_pgsql \
    xdebug redis @composer
EXPOSE 80



COPY php.ini /usr/local/etc/php/


