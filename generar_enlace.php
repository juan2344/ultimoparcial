<?php
// Genera un enlace público (solo lectura) para un estudiante
session_start();
require_once __DIR__ . '/config/bd.php';

if (!isset($_SESSION['usuario_id'])) { header('Location: /login.view.php'); exit; }

$rol = $_SESSION['rol'] ?? 'estudiante';
if ($rol !== 'profesor') { // opcional: deja que el propio estudiante también genere su link
    // Si quieres permitir que el estudiante genere su propio enlace, quita este if.
    header('Location: /panel.view.php'); exit;
}

// Parámetro: id del estudiante
$uid = isset($_GET['u']) ? (int)$_GET['u'] : 0;
if ($uid <= 0) { die('Falta el usuario_id'); }

// Generar token (64 hex) y expiración (7 días)
$token = bin2hex(random_bytes(32));
$expira = (new DateTime('+7 days'))->format('Y-m-d H:i:s');

// Insertar/actualizar: puedes permitir varios tokens por estudiante o invalidar previos
$stmt = $mysqli->prepare('INSERT INTO tokens_publicos (usuario_id, token, expira_en) VALUES (?,?,?)');
$stmt->bind_param('iss', $uid, $token, $expira);
$stmt->execute();
$stmt->close();

// Devuelve el enlace
$base = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$link = $base . '/public.php?u=' . $uid . '&k=' . $token;

