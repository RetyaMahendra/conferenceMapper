FROM php:7.2.34-apache 
RUN docker-php-ext-install mysqli
#COPY config/php.ini /usr/local/etc/php/
#COPY src/ /var/www/html/
