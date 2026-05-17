# -- Dependency install stage --
FROM composer:2 AS deps
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist

# -- Production image --
FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev unzip \
    && docker-php-ext-install opcache zip \
    && rm -rf /var/lib/apt/lists/*

# OPcache tuning for production
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.enable_cli=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

COPY --from=deps /app/vendor ./vendor
COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

# Stateless defaults — no database needed for sessions/cache/queue
ENV PORT=8080 \
    SESSION_DRIVER=array \
    CACHE_STORE=array \
    QUEUE_CONNECTION=sync
EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=$PORT
