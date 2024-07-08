FROM php:8.0-apache

COPY --from=composer /usr/bin/composer /usr/bin/composer

# install utils
RUN apt-get update \
 && apt-get install -y \
    git \
    wget \
    curl \
    zip \
    unzip \
    vim \
    libzip-dev

RUN docker-php-ext-install -j$(nproc) pdo_mysql exif zip

# Install extensions
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug,develop" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \ 
    && echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \ 
    && echo "xdebug.cli_color=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \ 
    && echo "xdebug.extended_info=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \ 
    && echo "xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/error_reporting.ini

# conf rewrite
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
 && cd /etc/apache2/mods-enabled \
 && ln -s ../mods-available/rewrite.load
