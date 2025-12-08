<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Super Administrador - Aventones</title>
    @vite('resources/css/menu.css')
</head>
<body>
  <header class="header">
    <div id="perfilSuperadmin" class="perfil-superadmin"></div>

    {{-- Aquí puedes usar una ruta Laravel en lugar de login.html --}}
    <button onclick="cerrarSesion()" class="btn-salir">Cerrar sesión</button>
  </header>

<section class="seccion">
    <h3>Gestión de Administradores</h3>
    <p>Aquí podrás crear, editar y desactivar administradores.</p>
</section>

</body>
</html>
