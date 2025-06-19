# ───────── Base PHP ─────────
FROM php:8.3-fpm-alpine

# Paquets nécessaires
RUN apk add --no-cache \
      libpng libjpeg-turbo freetype icu-libs libzip zlib \
      bash curl git unzip openssl-dev

# Extensions PHP + driver Mongo
RUN apk add --no-cache --virtual .build-deps \
      autoconf g++ make pkgconf \
      libpng-dev libjpeg-turbo-dev freetype-dev \
      icu-dev libzip-dev oniguruma-dev libxml2-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install intl pdo pdo_mysql opcache zip gd \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb \
  && apk del .build-deps

# Limites d’upload
RUN { \
      echo 'upload_max_filesize=10M'; \
      echo 'post_max_size=12M'; \
    } > /usr/local/etc/php/conf.d/zzz-uploads.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ───────── Code Symfony ─────────
WORKDIR /var/www/html
COPY app/ ./

ENV APP_ENV=prod APP_DEBUG=0 COMPOSER_ALLOW_SUPERUSER=1 \
    SYMFONY_SKIP_AUTO_SCRIPTS=1

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# ───────── Build front (Encore/Vite) ─────────
RUN apk add --no-cache nodejs npm \
  && npm install --omit dev \
  && npm run build           # crée public/build/

# ───────── Permissions finales ─────────
RUN rm -rf var/cache/* var/log/* \
  && mkdir -p var/cache/prod var/log app/uploads \
  && chown -R www-data:www-data var app/uploads \
  && chmod -R u+rwX,g+rwX var app/uploads

EXPOSE 9000
CMD ["php-fpm"]




