---
sidebar_position: 7
---

# Verificación del Push en DockerHub

Una vez ejecutado el Pipeline en Jenkins y completado el proceso de **build** y **push** de la imagen Docker, es necesario verificar que dicha imagen haya sido subida correctamente al repositorio configurado en **DockerHub**.

## Confirmar la imagen publicada

Al acceder a DockerHub, podrás ver el repositorio previamente creado, por ejemplo:

```
dark093/ultimoparcial
```

Dentro del repositorio, en la sección **Tags**, se muestran las versiones de la imagen que Jenkins publicó de forma automática.

En este caso aparecen dos etiquetas:

- **latest**
- **1.0.1**

Estas tags confirman que el pipeline ejecutó correctamente el proceso de construcción y subida de la imagen desde Jenkins hacia DockerHub.

![DockerHub Tags](./img/dockerhub-version.png)
_Pantalla donde se visualizan las imágenes generadas en DockerHub._\
