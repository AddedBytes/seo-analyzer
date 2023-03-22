FROM php:8.1-fpm-alpine

RUN apk update && \
  apk upgrade && \
  apk add \
    php8-mbstring \
    php8-bcmath \
    bash \
    git && \
  ln -sf \
    /usr/bin/php8 \
    /usr/bin/php && \
  rm -rf \
    /var/cache/apk/* \
    /etc/php8/*

RUN docker-php-ext-install mbstring bcmath

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /opt/app/

COPY . /opt/app
