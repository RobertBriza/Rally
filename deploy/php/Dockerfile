FROM php:8.2-fpm

USER root

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install mysqli pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD ./deploy/php /opt/rb
RUN chmod +x /opt/rb/entrypoint.sh

COPY ./ /var/www/html
WORKDIR /var/www/html

RUN mkdir -p temp && chmod -R 0777 temp
RUN mkdir -p log && chmod -R 0777 log

ARG SECRET_DIR

RUN mkdir -p $SECRET_DIR && chmod -R 0777 $SECRET_DIR

ARG PHP_ENV_TYPE
RUN composer install && composer dump-autoload -o --apcu

ENTRYPOINT ["/opt/rb/entrypoint.sh"]

CMD ["php-fpm"]

