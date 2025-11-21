FROM php:8.2-fpm

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Instalar Nginx, MariaDB y Supervisor
RUN apt-get update && \
    apt-get install -y nginx mariadb-server supervisor && \
    rm -rf /var/lib/apt/lists/*

# Copiar supervisord
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copiar Nginx
COPY nginx/default.conf /etc/nginx/sites-available/default

# Copiar proyecto PHP
WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80 3306

CMD ["/usr/bin/supervisord", "-n"]
