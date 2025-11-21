<?php
// controller/uploads.php
session_start();

// Archivo de configuración de la BD
require_once __DIR__ . '/../config/bd.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensaje'] = 'Debes iniciar sesión.';
    header('Location: ../views/login.view.php');
    exit;
}

// Recibir archivo de <input name="envio"> o <input name="archivo">
$file = $_FILES['envio'] ?? ($_FILES['archivo'] ?? null);

// Validar método y archivo
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$file) {
    $_SESSION['mensaje'] = 'Solicitud inválida.';
    header('Location: ../views/panel.view.php');
    exit;
}

// Validar errores de subida
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
    header('Location: ../views/panel.view.php');
    exit;
}

// Validar extensión permitida
$permitidos = ['pdf','doc','docx','xls','xlsx'];
$nombreOriginal = $file['name'];
$ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
if (!in_array($ext, $permitidos, true)) {
    $_SESSION['mensaje'] = 'Documentos con extensión no permitida.';
    header('Location: ../views/panel.view.php');
    exit;
}

// Crear carpeta uploads si no existe
$uploadsDir = __DIR__ . '/../uploads';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0775, true);
}

// Generar nombre único y mover archivo
$nombreAlmacenado = uniqid('doc_', true) . '.' . $ext;
$rutaAbs = $uploadsDir . '/' . $nombreAlmacenado;

if (!move_uploaded_file($file['tmp_name'], $rutaAbs)) {
    $_SESSION['mensaje'] = 'No se pudo mover el archivo al destino.';
    header('Location: ../views/panel.view.php');
    exit;
}

// Insertar en la base de datos
$uid = (int)$_SESSION['usuario_id'];

$sql = 'INSERT INTO archivos (usuario_id, nombre_original, guardado)
        VALUES (?, ?, ?)';
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    $_SESSION['mensaje'] = 'Error preparando inserción: ' . $mysqli->error;
    header('Location: ../views/panel.view.php');
    exit;
}

$stmt->bind_param('iss', $uid, $nombreOriginal, $nombreAlmacenado);
$ok = $stmt->execute();
$stmt->close();

// Mensaje final
$_SESSION['mensaje'] = $ok ? 'Archivo subido correctamente.' : 'No se pudo registrar el archivo en la base de datos.';
header('Location: ../views/panel.view.php');
exit;
