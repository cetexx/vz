FROM php:8.4-fpm

RUN apt-get update && apt-get install -y libzip-dev unzip
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin
RUN docker-php-ext-install zip pdo pdo_mysql
RUN pecl install redis && docker-php-ext-enable redis

COPY ./templates/php.conf /usr/local/etc/php-fpm.d/zzz-custom.conf

WORKDIR /srv/www

CMD ["php-fpm"]