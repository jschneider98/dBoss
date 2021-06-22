FROM php:5.6-alpine

# git is for composer
# zip is for composer

RUN apk add --no-cache \
        git \
        postgresql-dev \
        postgresql-client \
        libzip-dev \
    && docker-php-ext-install \
        pgsql pdo_pgsql \
        zip 

RUN curl -sS https://getcomposer.org/installer >composer-setup.php \
    && php composer-setup.php --quiet \
        --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

COPY ./ /usr/src/dboss
WORKDIR /usr/src/dboss

RUN composer install

CMD [ "php", "-S", "0.0.0.0:8080", "-t", "./public/", "public/index.php" ]
