<?php
$mysqli = new mysqli('db', 'gestoruser', 'gestorpass', 'gestor', 3306);
if ($mysqli->connect_errno) {
    die('Error de conexión: ' . $mysqli->connect_error);
}
$res = $mysqli->query('SELECT NOW() AS ahora');
$row = $res->fetch_assoc();
echo 'Conexión OK. Hora MySQL: ' . htmlspecialchars($row['ahora']);