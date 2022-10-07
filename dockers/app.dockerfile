FROM hovoh/php-laravel

WORKDIR /var/www

COPY . .
RUN composer install --optimize-autoloader --no-dev