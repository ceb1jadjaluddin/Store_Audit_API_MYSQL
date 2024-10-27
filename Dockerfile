FROM php:8.2-apache
COPY site/ /var/www/html/
RUN docker-php-ext-install pdo pdo_mysql
EXPOSE 80