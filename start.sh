#!/bin/bash
# Iniciar MariaDB
service mysql start

# Iniciar PHP-FPM en background
php-fpm8.2 -F &

# Iniciar Nginx en primer plano
nginx -g "daemon off;"
