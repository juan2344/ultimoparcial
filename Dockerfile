FROM php:8.2-fpm

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copiar aplicación al contenedor
WORKDIR /var/www/html
COPY . /var/www/html

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html
