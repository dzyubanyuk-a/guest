FROM php:8.3.13-fpm-alpine

RUN apk add --no-cache git libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql \
    && rm -rf /var/cache/apk/* /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN chown -R www-data:www-data /var/www

USER www-data

EXPOSE 9000

CMD ["php-fpm"]