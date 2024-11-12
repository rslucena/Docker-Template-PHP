FROM php:8.3.13-cli

ARG DEBIAN_FRONTEND=noninteractive
ENV TERM=xterm-color

#TIMEZONE
ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#UPDATE
RUN apt-get update && apt-get install vim -y && \
    apt-get install openssl -y && \
    apt-get install libssl-dev -y && \
    apt-get install wget -y && \
    apt-get install git -y && \
    apt-get install procps -y && \
    apt-get install htop -y

#VIM #GIT #SUPERVISOR
RUN apt-get update -y && apt-get install -yq vim git supervisor

#Libs
RUN apt-get install -y libcurl4-openssl-dev libbrotli-dev  libpq-dev libssl-dev unzip zlib1g-dev

#ZIP #OPCACHE #PDO
RUN docker-php-ext-install curl
RUN docker-php-ext-install pdo_mysql

#SWOOLE
RUN pecl install swoole && docker-php-ext-enable swoole

#REDIS
RUN pecl install -o -f redis &&  docker-php-ext-enable redis

#COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#REMOVE CACHE
RUN rm -r /var/lib/apt/lists/*
RUN rm -rf /tmp/pear

WORKDIR "/application"
COPY . .

CMD ["php", "/application/App/multithread.php"]

#SUPERVISOR
#COPY supervisord.conf /etc/supervisor/conf.d/supervisor.conf
#CMD ["/usr/bin/supervisord", "-n"]