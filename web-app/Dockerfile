FROM dunglas/frankenphp

ENV FRANKENPHP_CONFIG="worker /app/public/index.php watch" \
    MERCURE_PUBLISHER_JWT_KEY="supersecretjwtsecretforsocial"

# add additional extensions here:
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && install-php-extensions \
	pdo_mysql \
	opcache \
    bz2

# Add Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Production:
#RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Or development:
RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Caddyfile
#COPY ./Caddyfile /etc/frankenphp/Caddyfile

WORKDIR /app
