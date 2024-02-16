FROM dunglas/frankenphp:latest-alpine

WORKDIR /app

RUN apk add --no-cache \
		acl \
		file \
		gettext \
		git \
	;

RUN set -eux; \
	install-php-extensions \
		apcu \
		intl \
		bcmath \
		protobuf \
		opcache \
		zip \
	;

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV APP_ENV=prod

COPY docker/php.ini "$PHP_INI_DIR/php.ini"
COPY docker/app.ini $PHP_INI_DIR/conf.d/

COPY composer.* symfony.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

COPY . ./
RUN rm -Rf docker/

ENV SERVER_NAME=":80"
ENV FRANKENPHP_CONFIG="worker ./public/index.php"
ENV APP_RUNTIME="Runtime\\FrankenPhpSymfony\\Runtime"
ENV CADDY_GLOBAL_OPTIONS="debug"

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync; \
