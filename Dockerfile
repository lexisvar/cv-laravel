# lexisvar.me (Laravel) for Railway: one image, Apache + PHP 8.2.
# Base images are build args so a registry mirror can be used if needed.
ARG NODE_IMAGE=node:20-alpine
ARG PHP_IMAGE=php:8.2-apache
ARG COMPOSER_IMAGE=composer:2

# ---------- Admin assets (Vite/Tailwind) ----------
FROM ${NODE_IMAGE} AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund && test -x node_modules/.bin/vite
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build

FROM ${COMPOSER_IMAGE} AS composer

# ---------- Application ----------
FROM ${PHP_IMAGE}

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev libpq-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql pdo_pgsql gd zip opcache \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Production PHP settings and OPcache.
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && printf "opcache.enable=1\nopcache.validate_timestamps=0\nopcache.memory_consumption=128\nexpose_php=Off\nmemory_limit=256M\n" > "$PHP_INI_DIR/conf.d/zz-app.ini"

# Apache: our vhost on $PORT instead of the default site on :80.
COPY docker/apache.conf /etc/apache2/sites-available/app.conf
RUN echo "" > /etc/apache2/ports.conf \
    && a2dissite 000-default \
    && a2ensite app \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .
COPY --from=assets /app/public/build ./public/build
RUN composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod +x docker/start.sh

CMD ["docker/start.sh"]
