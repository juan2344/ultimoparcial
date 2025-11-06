<?php
session_start();
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['rol'] = $user['rol'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Credenciales incorrectas";
    }
}
?>
<form method="POST">
    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Ingresar</button>
</form>