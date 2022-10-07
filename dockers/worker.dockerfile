FROM hovoh/laravel-worker:latest

WORKDIR /var/www

COPY . .
RUN composer install --optimize-autoloader --no-dev