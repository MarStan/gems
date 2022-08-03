ARG APP_ENV=prod
ARG VERSION
ARG COMPOSER_MEMORY_LIMIT=-1

FROM php:8.1-apache as base

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN a2enmod rewrite headers

RUN apt-get update && \
    apt-get install -y git unzip libicu-dev libzip-dev libpng-dev

RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    intl \
    bcmath \
    zip \
    gd

RUN pecl install redis
RUN docker-php-ext-enable redis

RUN curl https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ci/docker/apache/vhost /etc/apache2/sites-available/000-default.conf
COPY ci/docker/apache/security.conf /etc/apache2/conf-available/security.conf

WORKDIR /var/www/html

COPY ci/docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod u+x /usr/local/bin/entrypoint

COPY ./composer.json ./composer.lock artisan ./
COPY database ./database/

FROM base as dev-install

RUN pecl install -f xdebug && \
    docker-php-ext-enable xdebug

COPY ci/docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

FROM ${APP_ENV}-install as final

ENTRYPOINT ["entrypoint"]
