FROM php:7.4-apache
LABEL maintainer="Alefe Souza <contact@alefesouza.com>"

RUN a2enmod rewrite

RUN apt-get update \
  && apt-get install -y libzip-dev unzip zlib1g-dev libicu-dev wget gnupg g++ git openssh-client libpng-dev iproute2 \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl pdo_mysql zip gd

RUN pecl install -f xdebug && docker-php-ext-enable xdebug;

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash

RUN apt-get update \
  && apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
