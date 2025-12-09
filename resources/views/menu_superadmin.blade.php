<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Administrador - Aventones</title>
    @vite('resources/js/app.js')
    @vite('resources/css/menu.css')
    @vite('resources/js/superadmin.js')
</head>
<body>

  <header class="header">
      <h2>Panel de Super Administrador</h2>

      <button onclick="cerrarSesion()" class="btn-salir">Cerrar sesión</button>
      </form>
  </header>

  <section class="seccion">
      <h3>Gestión de Usuarios</h3>

      <!-- FILTRO POR TIPO -->
      <label>Filtrar por tipo:</label>
      <select id="filtroTipo">
        <option value="administrador">Administrador</option>
        <option value="superadministrador">SuperAdmin</option>
        <option value="chofer">Chofer</option>
        <option value="pasajero">Pasajero</option>
      </select>

      <button class="btn-accion" onclick="cargarUsuarios()">Aplicar filtro</button>

      <!-- TABLA DE USUARIOS -->
      <table class="tabla" style="margin-top:20px;">
          <thead>
              <tr>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>Rol</th>
                  <th>Estado</th>
                  <th>Acción</th>
              </tr>
          </thead>
          <tbody id="tablaUsuarios"></tbody>
      </table>
  </section>

  <section class="seccion">
    <h3>Crear nuevo Administrador</h3>

    <div>
        <input type="text" id="newNombre" placeholder="Nombre">
        <input type="text" id="newApellido" placeholder="Apellido">
        <input type="email" id="newEmail" placeholder="Correo">
        <input type="password" id="newPassword" placeholder="Contraseña">

        <button type="button" class="btn-accion" onclick="crearAdmin()">
            Crear administrador
        </button>
    </div>
  </section>


</body>
</html>
