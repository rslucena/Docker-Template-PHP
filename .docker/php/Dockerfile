FROM php:8.2-fpm
WORKDIR "/application"

ARG DEBIAN_FRONTEND=noninteractive

#TIMEZONE
ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#UPDATE
RUN apt-get update && apt-get install -y --no-install-recommends apt-utils

#VIM #GIT #SUPERVISOR
RUN apt-get update -y && apt-get install -yq vim git supervisor

#ZIP #OPCACHE #PDO
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo_mysql

#SWOOLE
RUN pecl install swoole
RUN docker-php-ext-enable swoole

#COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#REMOVE CACHE
RUN rm -r /var/lib/apt/lists/*

#SUPERVISOR
COPY supervisord.conf /etc/supervisor/conf.d/supervisor.conf
CMD ["/usr/bin/supervisord", "-n"]