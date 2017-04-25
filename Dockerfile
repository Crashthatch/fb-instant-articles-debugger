FROM php:5.6-apache

RUN apt-get update && apt-get install -y git zip unzip && rm -rf /var/lib/apt/lists/*

COPY composer.json /var/www/html/
COPY composer.lock /var/www/html/
COPY composer.phar /var/www/html/

RUN php composer.phar install

COPY . /var/www/html/

