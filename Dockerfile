# Base PHP con FPM
FROM php:8.2-fpm

# Instalar extensiones PHP y paquetes necesarios
RUN apt-get update && apt-get install -y \
    nginx \
    mariadb-server mariadb-client \
    supervisor \
    curl unzip vim \
 && docker-php-ext-install pdo pdo_mysql mysqli \
 && apt-get clean

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar tu proyecto PHP
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Copiar configuración de Nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Copiar script de inicio que levanta todo
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Exponer puertos
EXPOSE 80 3306

# Comando principal
CMD ["/start.sh"]
