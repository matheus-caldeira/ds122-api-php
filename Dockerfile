FROM php:8.1.0-apache

WORKDIR /

COPY . .

RUN docker-php-ext-install pdo pdo_mysql mysqli

EXPOSE 80 443

CMD ["apache2-foreground"]