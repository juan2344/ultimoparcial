<?php
$DB_HOST = 'localhost';          
$DB_PORT = 3306;          
$DB_NAME = 'gestor';
$DB_USER = 'gestoruser';
$DB_PASS = 'gestorpass';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if ($mysqli->connect_errno) {
    die('Error de conexión MySQL: ' . $mysqli->connect_error);
}