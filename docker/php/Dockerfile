FROM php:8.3-fpm-alpine

# Paquets RUNTIME (conservés dans l'image)
RUN apk add --no-cache \
        nginx \
        libpng libjpeg-turbo freetype \
        icu-libs libzip zlib \
        bash curl shadow git unzip

# Paquets BUILD (supprimés ensuite) + compilation des extensions
RUN apk add --no-cache --virtual .build-deps \
        autoconf g++ make pkgconf \
        libpng-dev libjpeg-turbo-dev freetype-dev \
        icu-dev libzip-dev oniguruma-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip gd \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

# ---------- Composer ----------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ---------- Racine de l’appli ----------
WORKDIR /var/www/html

#-- optimise le cache -------
COPY app/composer.json app/composer.lock ./

ENV SYMFONY_SKIP_AUTO_SCRIPTS=1 \
    COMPOSER_ALLOW_SUPERUSER=1    \
    APP_ENV=prod APP_DEBUG=0

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts \
    && mkdir -p var/cache var/log \
    && chown -R www-data:www-data /var/www/html

COPY app/ .

RUN rm -rf var/cache/* var/log/* \
    && php bin/console cache:clear --env=prod \
    && chown -R www-data:www-data var


# ---------- Nginx & Entrypoint ----------
COPY docker/nginx/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]


