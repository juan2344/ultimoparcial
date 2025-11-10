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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel | Gestor de Documentos</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-br from-blue-50 via-white to-blue-100 text-gray-800 font-sans">

  <header class="relative bg-white/80 backdrop-blur-md shadow-md border-b border-blue-100 py-4 px-6 flex items-center justify-center">
    <h1 class="text-2xl sm:text-3xl font-bold text-blue-700 tracking-tight text-center">
      Gestor de Documentos
    </h1>

    <nav class="absolute right-6">
      <a href="index.php"
        class="flex items-center gap-2 bg-red-600 text-white px-5 py-2.5 rounded-full font-medium shadow-md hover:bg-red-700 hover:shadow-lg transition text-sm sm:text-base">
        <i class="ti ti-logout text-base"></i>
        <span>Salir</span>
      </a>
    </nav>
  </header>

  <main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10 max-w-5xl">

    <section class="bg-white/90 backdrop-blur-md rounded-3xl shadow-xl border border-blue-100 p-6 sm:p-8 transition hover:shadow-2xl">
      <h2 class="text-xl sm:text-2xl font-semibold text-blue-700 mb-6 flex items-center gap-2 justify-center sm:justify-start">
        <i class="ti ti-upload"></i> Subir nuevo documento
      </h2>

      <?php if (!empty($mensaje)): ?>
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-5 text-center sm:text-left">
          <?= htmlspecialchars($mensaje) ?>
        </div>
      <?php endif; ?>

      <form action="uploads.php" method="post" enctype="multipart/form-data" class="space-y-5">
        <div>
          <label for="envio" class="block text-gray-700 font-medium mb-1 text-sm sm:text-base">
            Selecciona un archivo:
          </label>
          <input type="file" name="envio" id="envio" accept=".pdf,.doc,.docx" required
            class="block w-full border border-gray-300 rounded-xl px-4 py-2 text-sm sm:text-base cursor-pointer bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
          <p class="text-xs sm:text-sm text-gray-500 mt-2">Formatos permitidos: <b>.pdf, .doc, .docx</b></p>
        </div>

        <button type="submit"
          class="w-full sm:w-auto flex items-center justify-center gap-2 bg-blue-600 text-white font-medium px-6 py-2.5 rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transform hover:-translate-y-0.5 transition text-sm sm:text-base mx-auto sm:mx-0">
          <i class="ti ti-send"></i> Enviar documento
        </button>
      </form>
    </section>

    <section class="bg-white/90 backdrop-blur-md rounded-3xl shadow-xl border border-blue-100 p-6 sm:p-8 transition hover:shadow-2xl">
      <h2 class="text-xl sm:text-2xl font-semibold text-blue-700 mb-6 flex items-center gap-2 justify-center sm:justify-start">
        <i class="ti ti-file-description"></i> Lista de documentos
      </h2>

      <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
        <table class="min-w-full text-sm sm:text-base text-gray-700">
          <thead class="bg-blue-600 text-white">
            <tr>
              <th class="px-4 sm:px-6 py-3 text-left font-semibold">Nombre del archivo</th>
              <th class="px-4 sm:px-6 py-3 text-left font-semibold">Fecha de creación</th>
              <th class="px-4 sm:px-6 py-3 text-center font-semibold">Descargar</th>
              <th class="px-4 sm:px-6 py-3 text-center font-semibold">Eliminar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($archivos as $key => $ar): ?>
              <tr class="<?= $key % 2 == 0 ? 'bg-gray-50' : 'bg-white' ?> hover:bg-blue-50 transition">
                <td class="px-4 sm:px-6 py-3 border-t border-gray-200 truncate"><?= htmlspecialchars($ar['nombre_original']) ?></td>
                <td class="px-4 sm:px-6 py-3 border-t border-gray-200"><?= htmlspecialchars($ar['creado_en']) ?></td>
                <td class="px-4 sm:px-6 py-3 border-t border-gray-200 text-center">
                  <a href="<?= htmlspecialchars($ar['ruta']) ?>" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium transition">
                    <i class="ti ti-download"></i> Descargar
                  </a>
                </td>
                <td class="px-4 sm:px-6 py-3 border-t border-gray-200 text-center">
                  <a href="<?= './delete.php?id=' . (int)$ar['id']; ?>"
                    onclick="return confirm('¿Eliminar este archivo?')"
                    class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 font-medium transition">
                    <i class="ti ti-trash"></i> Eliminar
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <footer class="bg-blue-900 text-gray-200 text-center py-5 mt-auto shadow-inner text-sm sm:text-base">
    <p class="flex flex-wrap items-center justify-center gap-2 px-4">
      <i class="ti ti-copyright"></i>
      2025 — Gestor de Documentos | Desarrollado por
      <span class="font-semibold text-white">Juan Pablo Garzón</span> y
      <span class="font-semibold text-white">Kevin Villegas</span>
    </p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</body>
</html>
