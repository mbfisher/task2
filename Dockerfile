FROM composer/composer

COPY composer.json /app/composer.json
COPY composer.lock /app/composer.lock

RUN composer install
