FROM php:8.2-fpm-alpine3.18

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
    apk add --no-cache nginx git supervisor

RUN set -eux; \
	install-php-extensions \
		apcu \
		intl \
		bcmath \
		opcache \
		zip \
	;

RUN mkdir -p \
        /etc/nginx/conf.d/ \
        /var/run/php-fpm/ \
        /var/log/supervisor && \
    mkdir -p /var/www &&\
    rm /usr/local/etc/php-fpm.d/zz-docker.conf

COPY ./docker/etc/ /usr/local/etc/
COPY ./docker/nginx /etc/nginx
COPY ./docker/supervisor /etc/supervisor

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR "/var/www"
COPY . ./

ENV APP_ENV=prod

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
    chown -R www-data:www-data /var/www; \
    chown -R www-data:www-data /var/run/php-fpm; \
	chmod +x bin/console; sync;

EXPOSE 80
ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisor.conf"]
