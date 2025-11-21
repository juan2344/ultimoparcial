---
sidebar_position: 8
---

# Guía de Despliegue con Docker Compose

## 1. Ubicarse en la carpeta del proyecto

Desde PowerShell o cualquier terminal, navega hasta el directorio donde se encuentra el archivo `docker-compose.yml`.  
Este archivo contiene la configuración completa de todos los servicios que se van a levantar (backend, base de datos, Nginx, phpMyAdmin, etc.).

---

## 2. Estructura del proyecto

La estructura esperada del proyecto es la siguiente:

```

/  # Carpeta raíz del proyecto
├── data/                 # Contiene el script SQL de inicialización
│   └── bd.sql            # Crea tablas y datos iniciales
│
├── default.conf          # Configuración de Nginx para PHP
│
├── docker-compose.yml    # Orquestación de contenedores
│
└── README.md             # Documentación del proyecto (opcional)

```

---

## 3. Archivo docker-compose.yml

```yaml
version: '3.9'

services:
  php:
    image: dark093/ultimoparcial:latest
    container_name: gestor-php
    environment:
      DB_HOST: db
      DB_PORT: 3306
      DB_NAME: gestor
      DB_USER: gestoruser
      DB_PASS: gestorpass
    networks:
      - gestor_net
    depends_on:
      - db

  nginx:
    image: nginx:latest
    container_name: gestor-nginx
    ports:
      - '8000:80'
    volumes:
      - ./default.conf:/etc/nginx/conf.d/default.conf:ro
    networks:
      - gestor_net
    depends_on:
      - php

  db:
    image: mariadb:latest
    container_name: gestor-db
    environment:
      MYSQL_ROOT_PASSWORD: rootpass
      MYSQL_DATABASE: gestor
      MYSQL_USER: gestoruser
      MYSQL_PASSWORD: gestorpass
    volumes:
      - db_data:/var/lib/mysql
      - ./data/bd.sql:/docker-entrypoint-initdb.d/bd.sql
    networks:
      - gestor_net

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: gestor-pma
    environment:
      PMA_HOST: db
      PMA_USER: gestoruser
      PMA_PASSWORD: gestorpass
    ports:
      - '8081:80'
    depends_on:
      - db
    networks:
      - gestor_net

networks:
  gestor_net:

volumes:
  db_data:
```

### ¿Qué hace este archivo?

Define y levanta automáticamente:

- PHP (aplicación principal)
- Nginx (servidor web)
- MariaDB (base de datos)
- phpMyAdmin (administrador visual)
- Redes internas
- Volúmenes persistentes

---

## 4. Archivo default.conf (Configuración de Nginx)

```nginx
server {
    listen 80;
    root /var/www/html;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME /var/www/html$fastcgi_script_name;
    }
}
```

---

## 5. Archivo de Base de Datos (data/bd.sql)

```sql
-- Tabla usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('estudiante', 'admin') DEFAULT 'estudiante'
);

-- Tabla archivos
CREATE TABLE archivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_original VARCHAR(255) NOT NULL,
    guardado VARCHAR(255) NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
```

### Credenciales de la base de datos

| Parámetro     | Valor      |
| ------------- | ---------- |
| Usuario       | gestoruser |
| Contraseña    | gestorpass |
| Base de datos | gestor     |

---

## 6. Servicio Nginx — Puertos

```yaml
nginx:
  ports:
    - '8000:80'
```

El sitio estará disponible en:

[http://localhost:8000](http://localhost:8000)

---

## 7. Tabla de Puertos del Proyecto

| Servicio   | Host → Contenedor | Descripción               |
| ---------- | ----------------- | ------------------------- |
| Nginx      | 8000 → 80         | Sitio web principal       |
| phpMyAdmin | 8081 → 80         | Administrar base de datos |

---

# 8. Comandos de Despliegue

Estos comandos permiten descargar la imagen desde Docker Hub y levantar los servicios definidos.

---

## 8.1 docker pull imagen

Este comando descarga una imagen desde Docker Hub hacia tu máquina local.

Ejemplo:

```bash
docker pull usuario/mi-app:latest
```

¿Qué hace?

- Descarga la imagen desde Docker Hub
- Obtiene la versión más reciente disponible
- Actualiza la copia local para usarla en el despliegue

---

## 8.2 docker-compose up -d

Inicia todos los servicios definidos en `docker-compose.yml`.

Ejemplo:

```bash
docker-compose up -d
```

¿Qué hace?

- Lee y ejecuta el archivo `docker-compose.yml`
- Crea y levanta los contenedores del proyecto
- El parámetro `-d` ejecuta todo en segundo plano (detached mode)
