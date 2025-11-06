<?php
// =================== LÓGICA DE LOGIN (mysqli) ===================
session_start();
require_once __DIR__ . '/config/bd.php'; // Aquí se define $mysqli (mysqli)

// Si envían el formulario:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // OJO: en tu formulario el input se llama "correo"
    // pero en la BD el campo correcto (según el esquema que venimos usando) es "email".
    $email    = trim($_POST['correo'] ?? '');   // viene del formulario
    $password = $_POST['password'] ?? '';

    // Validaciones básicas
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        // Si quieres, puedes guardar el error en sesión para mostrarlo en el HTML
        // $_SESSION['error_login'] = 'Correo o contraseña inválidos.';
        header('Location: /login.view.php');
        exit;
    }

    // Buscar usuario por email en la tabla "usuarios"
    // Si tu tabla tiene columnas: id, nombre, email, password_hash, rol
    $stmt = $mysqli->prepare('SELECT id, email, password_hash, rol FROM usuarios WHERE email = ? LIMIT 1');
    if (!$stmt) {
        // $_SESSION['error_login'] = 'Error preparando consulta.';
        header('Location: /login.view.php');
        exit;
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res  = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    // Validar credenciales
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['usuario_id'] = (int)$user['id'];
        $_SESSION['rol']        = ($user['rol'] === 'profesor') ? 'profesor' : 'estudiante';

        // Redirección según rol
        if ($_SESSION['rol'] === 'estudiante') {
            header('Location: /panel.view.php');
            exit;
        } else {
            header('Location: /admin.view.php');
            exit;
        }
    }

    // Si falló login: redirige (puedes guardar un mensaje si quieres)
    // $_SESSION['error_login'] = 'Correo o contraseña incorrectos.';
    header('Location: /login.view.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/formulario.css">
</head>

<body>

    <div class="contenedor">
        <div class="imagen">
            <img src="./img/freepik__the-style-is-candid-image-photography-with-natural__2573.png" alt="">
        </div>

        <form action="" method="post">
            <div class="text">
                <h1>Iniciar Sesión</h1>
            </div>
            <p>¡Bienvenido de nuevo! <br> Por favor, ingresa tus datos.</p>

            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required><br>

            <a href="#">¿Olvidaste tu contraseña?</a><br><br>
            <input type="submit" value="enviar">

            <div class="text">
                <p>¿No tienes una cuenta? <a href="/register.view.php">Regístrate aquí.</a></p>
            </div>
        </form>
    </div>
    
</body>
</html>