FROM php:8.0-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev wait-for-it acl && \
    pecl install apcu && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql && \
    docker-php-ext-enable apcu pdo_pgsql sodium && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

#RUN pecl install xdebug
#RUN docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD composer i -o ; wait-for-it db:5432 -- bin/console doctrine:database:create ; bin/console doctrine:migrations:migrate ; setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX ./var/cache ./var/log ; setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX ./var/cache ./var/log ; php-fpm
RUN usermod -u 1000 www-data

EXPOSE 9000
