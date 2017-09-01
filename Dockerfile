FROM eboraas/apache-php

RUN apt-get update && \
  apt-get -y install

RUN apt-get -y install curl \
  && mysql-client \
  && php-curl

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
