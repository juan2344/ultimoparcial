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

# Copiar proyecto
COPY . /var/www/html/

# Copiar config supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Script para inicializar DB
COPY docker/init-db.sh /init-db.sh
RUN chmod +x /init-db.sh

# PHP-FPM sin demonio
RUN sed -i "s/;daemonize = yes/daemonize = no/" /etc/php/*/fpm/php-fpm.conf

# MariaDB accesible externamente
RUN sed -i "s/\[mysqld\]/\[mysqld\]\nskip-networking=0\nbind-address=0.0.0.0/" \
    /etc/mysql/mariadb.conf.d/50-server.cnf

EXPOSE 80
EXPOSE 3306

CMD ["supervisord", "-n"]
