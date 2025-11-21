<?php
$DB_HOST = getenv('DB_HOST') ?: 'db';
$DB_PORT = getenv('DB_PORT') ?: 3306;
$DB_NAME = getenv('DB_NAME') ?: 'gestor';
$DB_USER = getenv('DB_USER') ?: 'gestoruser';
$DB_PASS = getenv('DB_PASS') ?: 'gestorpass';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($mysqli->connect_errno) {
    die("Error de conexión MySQL: " . $mysqli->connect_error);
}
