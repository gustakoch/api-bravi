FROM mlocati/php-extension-installer:latest AS php_extension_installer
FROM surnet/alpine-wkhtmltopdf:3.18.0-0.12.6-full as wkhtmltopdf
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu xdebug \
    && docker-php-ext-enable apcu xdebug \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

COPY --from=wkhtmltopdf /bin/wkhtmltopdf /bin/wkhtmltoimage /bin/libwkhtmltox.so /usr/local/bin/
COPY --from=php_extension_installer --link /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
    install-php-extensions \
      intl \
      zip \
      apcu \
      sqlite3 \
      xdebug \
      opcache \
      bcmath \
      pdo_mysql \
      gd \
      imagick \
      imap \
    ;

RUN    echo "xdebug.mode=debug" >>  /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini\
    && echo "xdebug.client_host=host.docker.internal" >>  /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini\
    && echo "xdebug.start_with_request=yes" >>  /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini\
    && echo "xdebug.remote_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini\
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini\
    && echo "xdebug.idekey=VSCODE" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini\
    && echo "xdebug.default_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /var/www/html
