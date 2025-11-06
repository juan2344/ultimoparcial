<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$rol = $_SESSION['rol'];
if ($rol === 'profesor') {
    $stmt = $pdo->query('SELECT * FROM archivos');
} else {
    $stmt = $pdo->prepare('SELECT * FROM archivos WHERE usuario_id = ?');
    $stmt->execute([$_SESSION['usuario_id']]);
}

$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Bienvenido, <?= htmlspecialchars($rol) ?></h1>
<a href="logout.php">Cerrar sesión</a>
<ul>
<?php foreach ($archivos as $a): ?>
    <li><?= htmlspecialchars($a['nombre_original']) ?> - <a href="<?= htmlspecialchars($a['ruta']) ?>">Descargar</a></li>
<?php endforeach; ?>
</ul>
<form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="archivo" required>
    <button type="submit">Subir archivo</button>
</form>