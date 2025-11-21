FROM php:8.2-cli

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

WORKDIR /var/www/html
COPY . /var/www/html

# Permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto
EXPOSE 8000

# Servidor interno PHP
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
