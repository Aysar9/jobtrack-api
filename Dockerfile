# 1. Basis-Image: PHP 8.3 mit FPM, schlanke Alpine-Linux-Variante
FROM php:8.3-fpm-alpine

# 2. System-Pakete, die zum Bauen der PHP-Erweiterungen nötig sind
RUN apk add --no-cache \
    icu-dev \
    $PHPIZE_DEPS

# 3. PHP-Erweiterungen installieren, die Symfony + MySQL brauchen
RUN docker-php-ext-install \
    pdo_mysql \
    intl \
    opcache

# 4. Composer aus dem offiziellen Composer-Image kopieren
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Arbeitsverzeichnis im Container festlegen
WORKDIR /var/www

# 6. Standard-Port von PHP-FPM
EXPOSE 9000

# 7. Was beim Start des Containers laufen soll
CMD ["php-fpm"]