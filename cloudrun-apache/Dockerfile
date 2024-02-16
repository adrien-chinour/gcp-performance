FROM php:8.2-apache

WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod

RUN apt update && apt install -y zip && apt clean

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY composer.* symfony.* ./
RUN composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

COPY . ./
COPY docker/apache2/default.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync;
