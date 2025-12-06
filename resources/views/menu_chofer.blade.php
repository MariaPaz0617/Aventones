<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú Chofer - Rides App</title>

  {{-- CSS con asset() --}}
  <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>
<body>

  <!-- Encabezado -->
  <header class="header">
    <div id="perfilChofer" class="perfil-chofer"></div>

    {{-- Aquí puedes usar una ruta Laravel en lugar de login.html --}}
    <button onclick="cerrarSesion()" class="btn-salir">Cerrar sesión</button>
  </header>

  <!-- Gestión de Vehículos -->
  <section class="seccion">
    <h2>Mis Vehículos</h2>
    <button class="btn-accion" onclick="abrirModal()">Registrar nuevo vehículo</button>

    <!-- Modal para registrar vehículo -->
    <div id="modalVehiculo" class="modal">
      <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal()">&times;</span>
        <h3 id="tituloModalVehiculo">Registrar nuevo vehículo</h3>

        <form id="formVehiculo" enctype="multipart/form-data">
          <input type="text" name="placa" placeholder="Placa" required>
          <input type="text" name="color" placeholder="Color">
          <input type="text" name="marca" placeholder="Marca">
          <input type="text" name="modelo" placeholder="Modelo">
          <input type="number" name="año" placeholder="Año">
          <input type="number" name="capacidad_asientos" placeholder="Capacidad de asientos" min="1" required>

          <div id="campoFoto">
            <input type="file" name="foto" accept="image/*">
          </div>

          <button type="submit" id="botonModalVehiculo" class="btn-accion">Guardar vehículo</button>
        </form>
      </div>
    </div>

    <div id="listaVehiculos"></div>
  </section>

  <!-- Gestión de Rides -->
  <section class="seccion">
    <h2>Mis Rides</h2>
    <button class="btn-accion" onclick="mostrarPanelRide()">Crear nuevo ride</button>

    <!-- Panel embebido para crear/editar ride -->
    <div id="panelRide" class="panel-oculto">
      <span class="cerrar" onclick="ocultarPanelRide()">&times;</span>
      <h3 id="tituloModalRide">Crear nuevo ride</h3>

      <form id="formRide">
        <input type="text" name="nombre" placeholder="Nombre del ride" required>
        <input type="text" name="lugar_salida" placeholder="Lugar de salida" required>
        <input type="text" name="lugar_llegada" placeholder="Lugar de llegada" required>
        <input type="date" name="fecha" required>
        <input type="time" name="hora" required>
        <input type="number" name="costo" placeholder="Costo" min="0" step="0.01" required>
        <input type="number" name="cantidad_espacios" placeholder="Espacios" min="1" required>

        <select name="vehiculo_id" required>
          <option value="">Seleccione un vehículo</option>
        </select>

        <button type="submit" id="botonModalRide" class="btn-accion">Guardar ride</button>
      </form>
    </div>

    <div id="listaRides">
      <table id="tablaRides" class="tabla-rides">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Salida</th>
            <th>Llegada</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Costo</th>
            <th>Espacios</th>
            <th>Vehículo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <p id="mensajeSinRides" class="sin-rides" style="display:none;">No tienes rides registrados aún.</p>
  </section>

  <!-- Reservas -->
  <section class="seccion">
    <h2>Solicitudes de Reserva</h2>
    <table id="tablaReservasChofer" class="tabla">
      <thead>
        <tr>
          <th>Pasajero</th>
          <th>Ruta</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </section>

  <!-- Perfil -->
  <section class="seccion">
    <h2>Mi Perfil</h2>
    <div id="perfilChoferPerfil"></div>

    <button class="btn-accion" onclick="mostrarPanel('panelEditarDatos')">Editar datos</button>
    <button class="btn-accion" onclick="mostrarPanel('panelContrasena')">Cambiar contraseña</button>
    <button class="btn-accion" onclick="mostrarPanel('panelFoto')">Cambiar fotografía</button>

    <!-- Panel editar datos -->
    <div id="panelEditarDatos" class="panel-oculto">
      <span class="cerrar" onclick="ocultarPanel('panelEditarDatos')">&times;</span>
      <form id="formEditarChofer">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <button type="submit" class="btn-accion">Actualizar</button>
      </form>
    </div>

    <!-- Panel cambiar contraseña -->
    <div id="panelContrasena" class="panel-oculto">
      <span class="cerrar" onclick="ocultarPanel('panelContrasena')">&times;</span>
      <form id="formContrasena">
        <input type="password" name="actual" placeholder="Contraseña actual" required>
        <input type="password" name="nueva" placeholder="Nueva contraseña" required>
        <input type="password" name="confirmar" placeholder="Confirmar nueva contraseña" required>
        <button type="submit" class="btn-accion">Actualizar contraseña</button>
      </form>
    </div>

    <!-- Panel cambiar fotografía -->
    <div id="panelFoto" class="panel-oculto">
      <span class="cerrar" onclick="ocultarPanel('panelFoto')">&times;</span>
      <form id="formFotoChofer" enctype="multipart/form-data">
        <input type="file" name="foto" accept="image/*" onchange="vistaPreviaFotoChofer(event)" required>
        <img id="previewFotoChofer" style="max-width:150px; margin-top:10px; display:none;">
        <button type="submit" class="btn-accion">Actualizar fotografía</button>
      </form>
    </div>
  </section>

  <footer class="footer">
    <p>&copy; 2025 Aventones. Maria Paz Ugalde - Xavier Fernández.</p>
  </footer>

  {{-- JS igual que en tu archivo original --}}
  <script>
      // TODO: tu JavaScript completo tal cual lo escribiste
      // Solo reemplaza rutas "../php/" por rutas Laravel si es necesario
  </script>

</body>
</html>
