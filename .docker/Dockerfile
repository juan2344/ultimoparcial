FROM php:8.2-fpm

# Extensiones necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Directorio de trabajo de la app
WORKDIR /var/www/html
