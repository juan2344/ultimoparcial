FROM debian:latest

RUN apt-get update && apt-get install -y \
    nginx \
    mariadb-server \
    php \
    php-fpm \
    php-mysql \
    php-zip \
    php-mbstring \
    php-xml \
    php-curl \
    supervisor

# Copia tu proyecto
COPY . /var/www/html/

# Copiar configuración de supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copiar SQL para inicializar DB
COPY docker/init.sql /docker-entrypoint-initdb.d/init.sql

# Configurar PHP-FPM para que no se ejecute en segundo plano
RUN sed -i "s/;daemonize = yes/daemonize = no/" /etc/php/*/fpm/php-fpm.conf

# Hacer accesible MariaDB desde fuera
RUN sed -i "s/\[mysqld\]/\[mysqld\]\nskip-networking=0\nbind-address=0.0.0.0/" \
    /etc/mysql/mariadb.conf.d/50-server.cnf

EXPOSE 80
EXPOSE 3306

CMD ["supervisord", "-n"]
