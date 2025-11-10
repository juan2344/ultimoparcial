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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200 text-gray-800">

  <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl flex flex-col md:flex-row max-w-5xl w-full mx-6 overflow-hidden">

    <div class="hidden md:flex md:w-1/2 bg-blue-600 items-center justify-center">
      <img src="./img/freepik__the-style-is-candid-image-photography-with-natural__2573.png"
        alt="Login ilustración"
        class="object-cover w-full h-full opacity-90" />
    </div>

    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
      <h1 class="text-3xl font-bold text-blue-700 mb-2 text-center md:text-left">Iniciar Sesión</h1>
      <p class="text-gray-600 mb-6 text-center md:text-left">
        ¡Bienvenido de nuevo! <br />Por favor, ingresa tus datos.
      </p>

      <form action="" method="post" class="space-y-5">

        <div>
          <label for="correo" class="block text-sm font-medium text-gray-700 mb-1">Correo:</label>
          <input type="email" name="correo" id="correo" required
            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition" />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña:</label>
          <div class="relative">
            <input type="password" name="password" id="password" required
              class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition" />
            <button type="button" id="togglePass"
              class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-blue-600 focus:outline-none">
              <i id="eyeIcon" class="ti ti-eye-off text-xl"></i>
            </button>
          </div>
        </div>

        <div class="text-right">
          <a href="#" class="text-sm text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
        </div>

        <input type="submit" value="Enviar"
          class="w-full bg-blue-600 text-white font-medium py-2.5 rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-300 cursor-pointer" />

        <p class="text-center text-gray-600">
          ¿No tienes una cuenta?
          <a href="/register.view.php" class="text-blue-600 font-medium hover:underline">
            Regístrate aquí
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
