FROM php:7.1.26-apache
LABEL maintainer="Alefe Souza <contact@alefesouza.com>"

RUN a2enmod rewrite

RUN apt-get update \
  && apt-get install -y zlib1g-dev libicu-dev wget gnupg g++ git openssh-client libpng-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl pdo_mysql zip

RUN pecl install xdebug-2.6.1 && docker-php-ext-enable xdebug

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash - \
  && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
  && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

RUN apt-get update \
  && apt-get install -y nodejs \
  && apt-get install -y yarn

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
