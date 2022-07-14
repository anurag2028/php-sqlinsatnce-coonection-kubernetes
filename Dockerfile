FROM php:7.0-apache
RUN docker-php-ext-install mysqli pdo
COPY src/ /var/www/html
EXPOSE 80
