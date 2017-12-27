FROM php:7.0.26-apache
COPY ./ /var/www/html/

RUN docker-php-ext-install pdo_mysql

RUN a2enmod rewrite

RUN apt-get update \
  && apt-get install -y zlib1g-dev libicu-dev g++ \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl

RUN apt-get install -y zlib1g-dev

RUN docker-php-ext-install zip

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash - \
  && apt-get install -y nodejs

RUN curl -o- -L https://yarnpkg.com/install.sh | bash

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
