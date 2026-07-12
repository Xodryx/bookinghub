# Backend image: PHP-FPM + extensions required by Laravel/MySQL + Composer
# PHP 8.4 to match the version Composer used to resolve the dependencies
FROM php:8.4-fpm

# System dependencies needed to build the PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer taken straight from the official image (no manual download)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Entrypoint: fixes storage/cache permissions on every startup
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]

# php-fpm listens on 9000: nginx forwards PHP requests to it
EXPOSE 9000
CMD ["php-fpm"]
