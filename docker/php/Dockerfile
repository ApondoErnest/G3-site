# syntax=docker/dockerfile:1

FROM php:8.5-cli-bookworm AS php-cli
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        gd \
        exif \
        intl \
        zip \
        bcmath \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

FROM php-cli AS composer-deps
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

FROM node:22-bookworm-slim AS assets
WORKDIR /var/www/html
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
COPY --from=composer-deps /var/www/html/vendor ./vendor
RUN npm run build

FROM php-cli AS vendor
COPY --from=composer-deps /var/www/html/vendor ./vendor
COPY . .
COPY --from=assets /var/www/html/public/build ./public/build
RUN mkdir -p \
        storage/app/private \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache
RUN APP_ENV=local \
    APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= \
    APP_DEBUG=true \
    DB_CONNECTION=sqlite \
    DB_DATABASE=:memory: \
    CACHE_STORE=array \
    SESSION_DRIVER=array \
    QUEUE_CONNECTION=sync \
    LOG_CHANNEL=stderr \
    composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi \
    && php artisan filament:upgrade \
    && chown -R www-data:www-data storage bootstrap/cache

FROM php:8.5-fpm-bookworm AS runtime
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        gd \
        exif \
        intl \
        zip \
        bcmath \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint
WORKDIR /var/www/html
COPY --from=vendor --chown=www-data:www-data /var/www/html /var/www/html
USER www-data
EXPOSE 9000
ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]

FROM php-cli AS test
RUN apt-get update \
    && apt-get install -y --no-install-recommends libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*
COPY composer.json composer.lock ./
RUN composer install \
    --no-interaction \
    --no-scripts \
    --prefer-dist
COPY . .
COPY --from=assets /var/www/html/public/build ./public/build
RUN mkdir -p \
        storage/app/private \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache
RUN APP_ENV=local \
    APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= \
    APP_DEBUG=true \
    DB_CONNECTION=sqlite \
    DB_DATABASE=:memory: \
    CACHE_STORE=array \
    SESSION_DRIVER=array \
    QUEUE_CONNECTION=sync \
    LOG_CHANNEL=stderr \
    ADMIN_MFA_REQUIRED=false \
    composer dump-autoload --optimize \
    && php artisan package:discover --ansi \
    && php artisan filament:upgrade
WORKDIR /var/www/html
CMD ["php", "artisan", "test", "--compact"]
