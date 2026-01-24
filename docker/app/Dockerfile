FROM php:8.4-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN docker-php-ext-install pdo pdo_mysql opcache pcntl

RUN apk add --no-cache \
    supervisor \
    bash \
    $PHPIZE_DEPS \
    && pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && apk del ${PHPIZE_DEPS}

# Copy opcache configration
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy php.ini overrides
COPY laravel.ini /usr/local/etc/php/conf.d
COPY php-fpm.conf /usr/local/etc/php-fpm.d/

# Add Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Production:
#RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Or development:
RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./start.sh /usr/local/bin/start
COPY ./supervisord.conf /etc/supervisor/supervisord.conf

WORKDIR /app
