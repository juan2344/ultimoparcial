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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200 text-gray-800">

  <div
    class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl flex flex-col md:flex-row max-w-5xl w-full mx-6 overflow-hidden">

    <div class="hidden md:flex md:w-1/2 bg-blue-600 items-center justify-center">
      <img src="./img/freepik__the-style-is-candid-image-photography-with-natural__2573.png"
        alt="Registro ilustración"
        class="object-cover w-full h-full opacity-90" />
    </div>

    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
      <h1 class="text-3xl font-bold text-blue-700 mb-2 text-center md:text-left">Crea tu cuenta</h1>
      <p class="text-gray-600 mb-6 text-center md:text-left">
        Únete a nuestra comunidad para acceder a tus tareas.
      </p>

      <form action="" method="post" class="space-y-5">

        <div>
          <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo:</label>
          <input type="text" name="nombre" id="nombre" placeholder="Ej. Juan Pérez" required
            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition" />
        </div>

        <div>
          <label for="correo" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico:</label>
          <input type="email" name="correo" id="correo" placeholder="ejemplo@correo.com" required
            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition" />
        </div>

        <div>
          <label for="pass" class="block text-sm font-medium text-gray-700 mb-1">Contraseña:</label>
          <div class="relative">
            <input type="password" name="pass" id="pass" placeholder="Crea tu contraseña segura" required
              class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition pr-10" />

            <button type="button" id="togglePass"
              class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-blue-600 focus:outline-none">
              <i id="eyeIcon" class="ti ti-eye-off text-2xl"></i>
            </button>
          </div>
        </div>

        <input type="submit" value="Registrarme"
          class="w-full bg-green-600 text-white font-medium py-2.5 rounded-xl shadow-md hover:bg-green-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 cursor-pointer mt-4" />

        <p class="text-center text-gray-600">
          ¿Ya tienes una cuenta?
          <a href="login.view.php" class="text-blue-600 font-medium hover:underline">
            Inicia sesión aquí
          </a>.
        </p>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="./js/toggle-password.js"></script>
</body>

</html>
