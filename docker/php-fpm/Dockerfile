FROM php:7.4-fpm

RUN apt-get update

#common
RUN apt-get  -y  install --no-install-recommends build-essential software-properties-common
RUN apt-get  -y  install psmisc

# Install git
RUN apt-get -y install git


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc && \
        composer



RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    supervisor


RUN docker-php-ext-install opcache
RUN docker-php-ext-install sockets

#set docker-user for 1000 uid
RUN useradd --shell /bin/bash -u 1000 -o -c "" -m docker-user

WORKDIR "/var/www/test-src"
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
