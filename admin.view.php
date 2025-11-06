<?php
require_once __DIR__ . '/config/bd.php'; // $mysqli (mysqli)
session_start();

/* 1) Validación de sesión y rol */
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /login.view.php');
    exit;
}
$rol = $_SESSION['rol'] ?? 'estudiante';
if ($rol !== 'profesor') {
    header('Location: /panel.view.php');
    exit;
}

/* 2) Traer todos los archivos con datos del estudiante
   — Esquema actual:
     usuarios.email, archivos.ruta, archivos.creado_en
*/
$sql = "
SELECT
    u.nombre                           AS nombre,
    u.email                            AS correo,
    a.nombre_original                  AS nombre_original,
    a.ruta                             AS ruta,
    DATE(a.creado_en)                  AS fecha
FROM usuarios AS u
JOIN archivos  AS a ON a.usuario_id = u.id
WHERE u.rol = 'estudiante'
ORDER BY a.creado_en DESC
";

$res = $mysqli->query($sql);
if ($res === false) {
    die('Error en consulta: ' . $mysqli->error);
}
$datosEstudiantes = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="./css/panel.css">
</head>
<body>
    <header style="text-align: center; display:flex; flex-direction:column;">
        <div>
            <h1>Panel de Gestión del Profesor</h1><br>
        </div>
        <div>
            <p>Desde este panel puede supervisar y gestionar todos los documentos enviados por los estudiantes. <br> 
            Puede realizar búsquedas, descargar archivos, revisar fechas de entrega y eliminar archivos si es necesario.</p>
        </div>
    </header>

    <!-- 🔹 Buscador -->
    <div style="margin:20px; text-align:center;">
        <input type="text" id="buscador" placeholder="Buscar estudiante..."
        style="padding:8px; width:300px; border-radius:6px; border:1px solid #ccc;">
    </div>

    <!-- 🔹 Contador -->
    <h3 style="text-align:center; margin:10px;">
        Total de documentos: <?php echo isset($datosEstudiantes) ? count($datosEstudiantes) : 0; ?>
    </h3>

    <main class="con">
        <table>
            <thead>
                <tr>
                    <th>Nombre Estudiante</th>
                    <th>Correo Electrónico</th>
                    <th>Descargar Documentos</th>
                    <th>Fecha</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($datosEstudiantes)): ?>
                    <?php foreach ($datosEstudiantes as $estudiante): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($estudiante['correo']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($estudiante['ruta']); ?>" target="_blank" rel="noopener">
                                <?php echo htmlspecialchars($estudiante['nombre_original']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($estudiante['fecha']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No hay documentos disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <!-- 🔹 Scripts -->
    <script>
        // Filtro rápido
        document.getElementById("buscador").addEventListener("keyup", function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll("tbody tr").forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // Ordenar columnas al hacer clic
        document.querySelectorAll("th").forEach((th, index) => {
            th.style.cursor = "pointer";
            th.addEventListener("click", () => {
                const rows = Array.from(document.querySelectorAll("tbody tr"));
                const asc = th.classList.toggle("asc");

                rows.sort((a, b) => {
                    const tdA = a.children[index].innerText.toLowerCase();
                    const tdB = b.children[index].innerText.toLowerCase();
                    return asc ? tdA.localeCompare(tdB) : tdB.localeCompare(tdA);
                });

                rows.forEach(row => document.querySelector("tbody").appendChild(row));
            });
        });
    </script>
</body>
</html>