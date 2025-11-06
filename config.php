<?php
$DB_HOST = 'db';
$DB_NAME = 'gestor';
$DB_USER = 'gestoruser';
$DB_PASS = 'gestorpass';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
    );
} catch (Throwable $e) {
    die('Error de conexión: ' . $e->getMessage());
}