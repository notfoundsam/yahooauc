FROM eboraas/apache-php

RUN apt-get update && \
  apt-get -y install

RUN apt-get -y install curl && \
  apt-get -y install mysql-client && \
  apt-get -y install php5-curl

COPY httpd.conf /etc/apache2/sites-available/000-default.conf
