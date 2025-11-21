FROM php:8.2-fpm

# Extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

WORKDIR /var/www/html

# Copiar proyecto al contenedor
COPY . /var/www/html
