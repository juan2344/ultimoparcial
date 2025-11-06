<?php
session_start();

require_once __DIR__ . '/config/bd.php'; // define $mysqli (mysqli)

if (!isset($_SESSION['usuario_id'])) {
    header('Location: register.view.php');
    exit;
}

// Mensaje flash (opcional)
$mensaje = '';
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}

// Traer archivos del usuario logueado
$usuarioId = (int)$_SESSION['usuario_id'];

// Selecciona campos que existen en tu tabla `archivos`:
// id, nombre_original, ruta, creado_en
$stmt = $mysqli->prepare(
    'SELECT id, nombre_original, ruta, creado_en
     FROM archivos
     WHERE usuario_id = ?
     ORDER BY creado_en DESC'
);
if (!$stmt) {
    die('Error preparando consulta: ' . $mysqli->error);
}
$stmt->bind_param('i', $usuarioId);
$stmt->execute();
$resul = $stmt->get_result();
$stmt->close();

$archivos = $resul ? $resul->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel</title>
    <link rel="stylesheet" href="./css/panel.css">
    <style>
        table {
            border-collapse: collapse;
            border: 2px solid #000;
        }

        td,
        th {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <div class="titulo">
            <h1>Gestor de Documentos</h1>
        </div>
        
        <nav>
            <ul>
                <li><a href="index.php>">Inicio</a></li>
            </ul>
        </nav>
    </header>


    <form action="uploads.php" method="post" enctype="multipart/form-data">
        <?php if (!empty($mensaje)): ?>
            <div style="background-color: #d4edda; padding: 10px; border-radius: 5px; color: #155724; margin-bottom: 10px;">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>
        <label for="envio">Subir nuevo documento</label><br>

        <input type="file" name="envio" id="envio"><br>
        <h5>documentos con extencion .pdf .doc .docx</h5>
        <input type="submit" value="Enviar">
    </form>

    <main class="con">
        <h2>Lista de documentos</h2>
        <table style="border:1px solid black;">
            <thead>
                <tr>
                    <th style="border:1px solid black;">nombre archivo</th>
                    <th style="border:1px solid black;">fecha archivo</th>
                    <th style="border:1px solid black;">Descargar archivo</th>
                    <th style="border:1px solid black;">eliminar archivos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archivos as $key => $ar): ?>
                    <?php $key = $key + 1; ?>
                    <?php $dato = $key % 2 == 0 ? "rgba(204, 204, 204, 0.84)" : "white"; ?>
                    <tr style="background-color: <?= htmlspecialchars($dato) ?>;">
                        <td><?= htmlspecialchars($ar['nombre_original']) ?></td>
                        <td><?= htmlspecialchars($ar['creado_en']) ?></td>
                        <td><a href="<?= htmlspecialchars($ar['ruta']) ?>" target="_blank" rel="noopener">Descargar</a></td>
                        <td><a href="<?= './delete.php?id=' . (int)$ar['id']; ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </main>
    <footer>
        <p>&copy; 2025 Mi Aplicación. Todos los derechos reservados.</p>
    </footer>
</body>

</html>