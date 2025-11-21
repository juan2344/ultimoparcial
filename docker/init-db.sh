#!/bin/bash

# Esperar a que MariaDB arranque
sleep 5

if [ ! -d "/var/lib/mysql/gestor" ]; then
    echo "Inicializando base de datos..."

    mysql -u root <<EOF
CREATE DATABASE gestor;
CREATE USER 'admin'@'%' IDENTIFIED BY 'admin123';
GRANT ALL PRIVILEGES ON gestor.* TO 'admin'@'%';
FLUSH PRIVILEGES;
EOF

    echo "✔ Base de datos creada"
else
    echo "Base ya existe, no se crea"
fi
