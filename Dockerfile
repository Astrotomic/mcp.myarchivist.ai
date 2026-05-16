# -- Dependency install stage --
FROM composer:2 AS deps
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist

# -- Production image --
FROM php:8.4-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev unzip \
    && docker-php-ext-install opcache zip \
    && a2dismod mpm_event \
    && a2enmod mpm_prefork rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Apache port config — __PORT__ placeholder is replaced at container start
RUN echo 'Listen __PORT__' > /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:__PORT__>/' /etc/apache2/sites-available/000-default.conf

# Point document root at Laravel's public/ directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

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

ENV PORT=8080
EXPOSE 8080

# At runtime: substitute the port placeholder, then start Apache.
# config:cache and route:cache are NOT run at build time because
# env vars (APP_KEY, APP_URL, etc.) are only available at runtime,
# and closure-based routes cannot be serialized by route:cache.
CMD sh -c "sed -i \"s/__PORT__/$PORT/g\" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"
