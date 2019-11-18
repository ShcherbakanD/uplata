FROM php:7.3.5-cli-alpine3.9
ENV PHP_XDEBUG_VERSION 2.7.2

RUN apk --update upgrade \
    && apk add --no-cache vim autoconf automake make gcc g++ libtool pkgconfig libmcrypt-dev git zlib-dev postgresql-dev openssl-dev openssh supervisor yaml-dev \
    && docker-php-ext-install bcmath sockets pdo pdo_pgsql pgsql

RUN pecl channel-update pecl.php.net \
    && pecl install xdebug-${PHP_XDEBUG_VERSION} \
    && pecl install yaml \
    && docker-php-ext-enable xdebug yaml

# Composer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer

COPY php.ini $PHP_INI_DIR/php.ini
WORKDIR /var/www/html