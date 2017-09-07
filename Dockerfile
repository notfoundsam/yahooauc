FROM eboraas/apache-php

RUN apt-get update && \
  apt-get -y install

RUN apt-get -y install curl
RUN apt-get -y install mysql-client

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN a2enmod rewrite
RUN a2enmod headers

WORKDIR /var/www/
