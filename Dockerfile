FROM php:8.1.0-apache

ARG user
ARG uid

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN useradd -G www-data,root -u $uid -d /home/$user $user

USER $user

WORKDIR /var/www/html

EXPOSE 80 443

CMD ["apache2-foreground"]