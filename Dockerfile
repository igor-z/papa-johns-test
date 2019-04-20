FROM php:7.1-fpm

RUN pecl install xdebug-2.7.1 && docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && docker-php-ext-install pdo pdo_mysql