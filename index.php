<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestor de Documentos Universitarios</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-br from-blue-100 via-white to-blue-200 text-gray-800">

  <header class="bg-white/70 backdrop-blur-md shadow-sm py-6 flex justify-center items-center gap-3">
    <i class="ti ti-school text-blue-700 text-3xl"></i>
    <h1 class="text-3xl font-bold text-blue-700">
      Gestor de Documentos Universitarios
    </h1>
  </header>

  <main class="flex-1 flex items-center justify-center p-6">
    <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-2xl p-8 md:p-12 flex flex-col md:flex-row items-center gap-10 max-w-5xl w-full">

      <div class="w-full md:w-1/2">
        <img src="./img/freepik__the-style-is-candid-image-photography-with-natural__33405.png"
          alt="Documentos universitarios"
          class="rounded-2xl shadow-lg w-full hover:scale-105 transition-transform duration-500" />
      </div>

      <div class="w-full md:w-1/2 text-center md:text-left space-y-6">
        <h2 class="text-2xl font-semibold text-blue-700 flex items-center justify-center md:justify-start gap-2">
          <i class="ti ti-user-circle text-3xl text-blue-600"></i>
          Bienvenido
        </h2>
        <p class="text-lg leading-relaxed text-gray-700">
          Esta plataforma permite a los <span class="font-semibold text-blue-600">estudiantes</span> subir y gestionar
          sus documentos de forma segura, mientras los <span class="font-semibold text-blue-600">profesores</span>
          pueden revisarlos fácilmente desde cualquier lugar.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start pt-2">
          <a href="./views/login.view.php"
            class="flex items-center justify-center gap-2 px-8 py-3 bg-blue-600 text-white font-medium rounded-full shadow-md hover:bg-blue-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
            <i class="ti ti-login"></i>
            Iniciar Sesión
          </a>

          <a href="./views/register.view.php"
            class="flex items-center justify-center gap-2 px-8 py-3 bg-green-600 text-white font-medium rounded-full shadow-md hover:bg-green-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
            <i class="ti ti-user-plus"></i>
            Registrarse
          </a>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-blue-900 text-gray-200 text-center py-4 mt-auto">
    <p class="text-sm flex items-center justify-center gap-2">
      <i class="ti ti-copyright text-sm"></i>
      2025 — Creado por 
      <span class="font-semibold text-white">Juan Pablo Garzón</span> y
      <span class="font-semibold text-white">Kevin Villegas</span>
    </p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
</body>
</html>
