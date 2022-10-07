FROM hovoh/php-laravel

RUN apk add --update nodejs npm php-gd libpng-dev libjpeg-turbo libjpeg-turbo-dev
RUN docker-php-ext-configure gd --enable-gd --with-jpeg
RUN docker-php-ext-install gd
WORKDIR /var/www
