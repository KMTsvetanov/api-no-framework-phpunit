FROM php:8.0.2-fpm

RUN apt-get update

COPY ./etc/php/php.ini $PHP_INI_DIR/
COPY ./etc/php/xdebug.ini $PHP_INI_DIR/conf.d/
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

RUN apt-get install -y git
RUN apt-get install -y nano
RUN apt-get install -y zip
RUN apt-get install -y unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y curl \
  && curl -sL https://deb.nodesource.com/setup_9.x | bash - \
  && apt-get install -y nodejs \
  && curl -L https://www.npmjs.com/install.sh | sh

RUN npm cache clean -f
RUN npm install -g n
RUN n stable