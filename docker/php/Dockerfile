FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
        libpng libjpeg-turbo freetype \
        icu-libs libzip zlib \
        openssl-dev \
        bash curl shadow git unzip

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

RUN echo "upload_max_filesize=10M" > /usr/local/etc/php/conf.d/zzz-uploads.ini \
    && echo "post_max_size=12M" >> /usr/local/etc/php/conf.d/zzz-uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY app/composer.json app/composer.lock ./

ENV SYMFONY_SKIP_AUTO_SCRIPTS=1 \
    COMPOSER_ALLOW_SUPERUSER=1    \
    APP_ENV=prod APP_DEBUG=0

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts \
    && mkdir -p var/cache var/log \
    && chown -R www-data:www-data /var/www/html

COPY app/ ./

RUN rm -rf var/cache/* var/log/* \
    && chown -R www-data:www-data var

RUN mkdir -p /var/www/html/public/uploads/users \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 775 /var/www/html/public/uploads

EXPOSE 9000
CMD ["php-fpm"]



