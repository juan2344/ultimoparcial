<?php
$DB_HOST = getenv('DB_HOST') ?: 'gestor-db';
$DB_PORT = getenv('DB_PORT') ?: 3306;
$DB_NAME = getenv('DB_NAME') ?: 'gestor';
$DB_USER = getenv('DB_USER') ?: 'gestoruser';
$DB_PASS = getenv('DB_PASS') ?: 'gestorpass';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

// Si falla la conexión, mostrar un mensaje claro
if ($mysqli->connect_errno) {
    die('Error de conexión MySQL: ' . $mysqli->connect_error);
}
