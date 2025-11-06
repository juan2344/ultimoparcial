<?php
// delete.php (mysqli)
session_start();
require_once __DIR__ . '/config/bd.php'; // define $mysqli (mysqli)

// Debe estar logueado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensaje'] = 'Debes iniciar sesión.';
    header('Location: /login.view.php');
    exit;
}

$uid = (int)$_SESSION['usuario_id'];
$id  = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['mensaje'] = 'ID de archivo inválido.';
    header('Location: /panel.view.php');
    exit;
}

// 1) Obtener el archivo y validar que pertenezca al usuario
$stmt = $mysqli->prepare('SELECT ruta FROM archivos WHERE id = ? AND usuario_id = ? LIMIT 1');
if (!$stmt) {
    $_SESSION['mensaje'] = 'Error preparando consulta: ' . $mysqli->error;
    header('Location: /panel.view.php');
    exit;
}
$stmt->bind_param('ii', $id, $uid);
$stmt->execute();
$res = $stmt->get_result();
$file = $res->fetch_assoc();
$stmt->close();

if (!$file) {
    // No existe o no pertenece al usuario
    $_SESSION['mensaje'] = 'No tienes permiso para eliminar este archivo o no existe.';
    header('Location: /panel.view.php');
    exit;
}

// 2) Borrar archivo físico
$rutaRel = $file['ruta'];              // p.ej. 'uploads/doc_xyz.pdf'
$rutaAbs = __DIR__ . '/' . $rutaRel;   // path absoluto

if (is_file($rutaAbs)) {
    @unlink($rutaAbs);
}

// 3) Borrar de la base de datos
$stmt = $mysqli->prepare('DELETE FROM archivos WHERE id = ? AND usuario_id = ?');
if (!$stmt) {
    $_SESSION['mensaje'] = 'Error preparando eliminación: ' . $mysqli->error;
    header('Location: /panel.view.php');
    exit;
}
$stmt->bind_param('ii', $id, $uid);
$ok = $stmt->execute();
$stmt->close();

// 4) Mensaje y regreso al panel
$_SESSION['mensaje'] = $ok ? 'Archivo eliminado correctamente.' : 'No se pudo eliminar el archivo de la base de datos.';
header('Location: /panel.view.php');
exit;