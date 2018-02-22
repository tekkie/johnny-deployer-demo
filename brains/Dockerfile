FROM php:fpm
RUN docker-php-ext-enable opcache

# Run docker-php-ext-install for available extensions
RUN apt-get -y update \
    && apt-get install -y libicu-dev git unzip\
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl


# install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_enable = 1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back = 0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart = 1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey = \"stormbringer\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_port = 9009" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_host = 10.254.254.254" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN COMPOSER_ALLOW_SUPERUSER=1 composer --version

WORKDIR /var/www/johnny/the-brains

RUN echo 'alias sf="php /var/www/johnny/the-brains/bin/console"' >> ~/.bashrc

# prepare files for dependency installation
COPY composer* ./

# grab all 3rd party dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install
