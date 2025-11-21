---
sidebar_position: 2
---

# Configuración de Docker Compose

## Paso 1: Navegar a la carpeta del proyecto

Accede al directorio donde se encuentra el archivo `docker-compose.yml`.  
Este archivo contiene la configuración de todos los servicios que se van a levantar (backend, base de datos, frontend, etc.).

## Paso 2: Ejecutar Docker Compose

Ejecuta el siguiente comando para crear y levantar los contenedores en **segundo plano**:

```bash
docker-compose up -d
```

## Paso 3: Verificar que los contenedores estén corriendo

Puedes comprobar el estado de los contenedores con:

```bash
docker-compose ps
```

## Imagen de referencia

![Docker Compose](./img/docker-compose.png)
_Ejemplo de salida al ejecutar `docker-compose up -d`._
