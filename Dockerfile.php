FROM php:7.4-fpm-alpine

ARG PHPGROUP
ARG PHPUSER

ENV PHPGROUP=${PHPGROUP}
ENV PHPUSER=${PHPUSER}

RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}; exit 0

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN apk --no-cache add libzip-dev git && git config --global --add safe.directory /var/www/html
RUN apk --no-cache add libpng-dev freetype-dev libjpeg-turbo-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install zip pdo pdo_mysql

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

COPY --chown=root:root . /var/www/html
RUN git remote set-url origin http://tiennguyen:Kaito1%403@magicak.com:8000/magicak-web/magicak.git && git config --global --add safe.directory /var/www/html && git checkout develop && git pull

EXPOSE 9000

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
