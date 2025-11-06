<?php
// uploads.php (versión mysqli)
session_start();
require_once __DIR__ . '/config/bd.php'; // aquí se define $mysqli (mysqli)

// Debe estar logueado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensaje'] = 'Debes iniciar sesión.';
    header('Location: /login.view.php');
    exit;
}

// Acepta el archivo desde <input name="envio"> o <input name="archivo">
$file = $_FILES['envio'] ?? ($_FILES['archivo'] ?? null);

// Validar método y archivo
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$file) {
    $_SESSION['mensaje'] = 'Solicitud inválida.';
    header('Location: /panel.view.php');
    exit;
}

// Validar error de subida
if ($file['error'] !== UPLOAD_ERR_OK) {
    $map = [
        UPLOAD_ERR_INI_SIZE   => 'El archivo excede upload_max_filesize.',
        UPLOAD_ERR_FORM_SIZE  => 'El archivo excede el límite del formulario.',
        UPLOAD_ERR_PARTIAL    => 'El archivo se subió parcialmente.',
        UPLOAD_ERR_NO_FILE    => 'No se seleccionó archivo.',
        UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal.',
        UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir en disco.',
        UPLOAD_ERR_EXTENSION  => 'Una extensión detuvo la subida.'
    ];
    $_SESSION['mensaje'] = $map[$file['error']] ?? ('Error de subida: ' . $file['error']);
    header('Location: /panel.view.php');
    exit;
}

// Validar extensión
$permitidos = ['pdf','doc','docx','xls','xlsx'];
$nombreOriginal = $file['name'];
$ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
if (!in_array($ext, $permitidos, true)) {
    $_SESSION['mensaje'] = 'documentos con extencion no requerida';
    header('Location: /panel.view.php');
    exit;
}

// Asegurar carpeta de destino
$uploadsDirAbs = __DIR__ . '/uploads';
if (!is_dir($uploadsDirAbs)) {
    @mkdir($uploadsDirAbs, 0775, true);
}

// Generar nombre único y mover
$nombreAlmacenado = uniqid('doc_', true) . '.' . $ext;
$rutaRel = 'uploads/' . $nombreAlmacenado;    // lo que guardamos en BD
$rutaAbs = $uploadsDirAbs . '/' . $nombreAlmacenado;

if (!move_uploaded_file($file['tmp_name'], $rutaAbs)) {
    $_SESSION['mensaje'] = 'No se pudo mover el archivo al destino.';
    header('Location: /panel.view.php');
    exit;
}

// Datos para BD
$uid    = (int)$_SESSION['usuario_id'];
$tipo   = $file['type'] ?? '';
$tamano = (int)$file['size'];

// INSERT acorde a tu tabla `archivos`
// Si tu tabla es: usuario_id, nombre_original, nombre_almacenado, tipo, tamano, ruta
$sql = 'INSERT INTO archivos (usuario_id, nombre_original, nombre_almacenado, tipo, tamano, ruta)
        VALUES (?, ?, ?, ?, ?, ?)';
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    $_SESSION['mensaje'] = 'Error preparando inserción: ' . $mysqli->error;
    header('Location: /panel.view.php');
    exit;
}
$stmt->bind_param('isssis', $uid, $nombreOriginal, $nombreAlmacenado, $tipo, $tamano, $rutaRel);
$ok = $stmt->execute();
$stmt->close();

$_SESSION['mensaje'] = $ok ? 'Archivo subido correctamente.' : 'No se pudo registrar el archivo en la base de datos.';
header('Location: /panel.view.php');
exit;
