CREATE DATABASE IF NOT EXISTS gestor;
USE gestor;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    pass VARCHAR(255) NOT NULL,
    rol ENUM('estudiante', 'admin') DEFAULT 'estudiante'
);

CREATE TABLE IF NOT EXISTS archivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_original VARCHAR(255) NOT NULL,
    guardado VARCHAR(255) NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE USER IF NOT EXISTS 'gestoruser'@'%' IDENTIFIED BY 'gestorpass';
GRANT ALL PRIVILEGES ON gestor.* TO 'gestoruser'@'%';
FLUSH PRIVILEGES;
