<?php
// === LÓGICA (no cambia la parte visual) ===
require_once __DIR__ . '/config/bd.php'; // usa $mysqli desde config/bd.php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Datos del formulario (mismos names del HTML: nombre, correo, pass)
    $rol      = isset($_POST['rol']) ? $_POST['rol'] : 'estudiante';
    $nombre   = trim($_POST['nombre'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');
    $passPlan = $_POST['pass'] ?? '';

    // Validaciones mínimas
    if ($nombre === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($passPlan) < 6) {
        // Si quieres, puedes guardar un mensaje en sesión y mostrarlo en la vista
        header('Location: register.view.php');
        exit();
    }

    // 1) Verificar si ya existe ese correo
    $stmt = $mysqli->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
    if (!$stmt) {
        header('Location: register.view.php'); exit();
    }
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // Ya existe ese correo
        $stmt->close();
        header('Location: register.view.php');
        exit();
    }
    $stmt->close();

    // 2) Insertar usuario
    $hash = password_hash($passPlan, PASSWORD_DEFAULT);

    // OJO: estas columnas están pensadas para la tabla creada con:
    // nombre, email, password_hash, rol
    // Si tu tabla usa 'correo' y 'pass', cambia el INSERT por:
    // INSERT INTO usuarios (nombre, correo, pass, rol) VALUES (?,?,?,?)
    $sql  = 'INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?,?,?,?)';
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        header('Location: register.view.php'); exit();
    }
    $stmt->bind_param('ssss', $nombre, $correo, $hash, $rol);
    $stmt->execute();
    $nuevoId = $mysqli->insert_id;
    $stmt->close();

    // 3) Crear sesión y redirigir al panel
    $_SESSION['rol']        = $rol;
    $_SESSION['usuario_id'] = (int)$nuevoId;

    header('Location: panel.view.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="./css/formulario.css">
</head>
<body>

    <div class="contenedor">
        <div class="imagen">
            <img src="./img/freepik__the-style-is-candid-image-photography-with-natural__2573.png" alt="">
        </div>

        <form action="" method="post">
            <div class="text">
                <h1>Crea tu cuenta</h1>
            </div>
            <p>Únete a nuestra comunidad para acceder a tus tareas.</p>

            <label for="nombre">Nombre completo:</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ej. Juan Pérez" required>

            <label for="correo">Correo electrónico:</label>
            <input type="email" name="correo" id="correo" placeholder="ejemplo@correo.com" required>

            <label for="pass">Contraseña:</label>
            <input type="password" name="pass" id="pass" placeholder="Crea tu contraseña segura" required>

            <!-- Si tu UI necesita elegir rol, puedes añadir un select aquí con name="rol" -->

            <input style="margin-top:30px;" type="submit" value="Registrarme">
            <div class="text" style="text-aling:left;" >
                <p>¿Ya tienes una cuenta? <a href="login.view.php">Inicia sesión aquí.</a></p>
            </div>
        </form>
    </form>
    </div>
</body>
</html>