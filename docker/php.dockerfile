FROM php:8-fpm-alpine

ARG WWWGROUP

ENV UID=${WWWGROUP}
ENV GID=${WWWGROUP}
RUN mkdir -p /var/www/html

WORKDIR /var/www/html

# MacOS staff group's gid is 20, so is the dialout group in alpine linux. We're not using it, let's just remove it.
RUN delgroup dialout

RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

RUN docker-php-ext-install pdo pdo_mysql

COPY crontab /etc/crontabs/root

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis
COPY ./php/laravel.ini /usr/local/etc/php/conf.d
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

RUN apt-get -y update \
&& apt-get install -y libicu-dev \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl
RUN  docker-php-ext-configure exif
RUN  docker-php-ext-install exif
RUN docker-php-ext-enable intl
RUN docker-php-ext-enable exif
