<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menú Pasajero - Rides App</title>

    @vite('resources/js/app.js')
    @vite('resources/css/menu.css')
    @vite('resources/js/pasajero.js')
</head>
<body>

<header id="headerPasajero" class="header">
    <div id="perfilPasajero" class="perfil-chofer"></div>

    <!-- Cerrar sesión igual que en chofer -->
    <button onclick="cerrarSesion()" class="btn-salir">Cerrar sesión</button>
</header>

<main>
    <!-- Sección: Buscar Rides -->
    <section class="seccion">
        <h2>Buscar Rides</h2>

        <div class="filtros-busqueda">
            <input type="text" id="origen" placeholder="Lugar de salida">
            <input type="text" id="destino" placeholder="Lugar de llegada">

            <button onclick="filtrarRides()">Buscar</button>

            <select id="ordenarPor" onchange="ordenarRides()">
                <option value="fecha">Fecha</option>
                <option value="lugar_salida">Lugar de salida</option>
                <option value="lugar_llegada">Lugar de llegada</option>
            </select>

            <select id="ordenDireccion" onchange="ordenarRides()">
                <option value="asc">Ascendente</option>
                <option value="desc">Descendente</option>
            </select>
        </div>

        <div id="listaRidesPublicos"></div>
    </section>

    <section id="seccionReservas" class="seccion">
        <h2>Mis Reservas</h2>
        <table id="tablaReservas" class="tabla">
            <thead>
            <tr>
                <th>Ride</th>
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

    <section class="seccion">
        <h2>Mi Perfil</h2>

        <div id="perfilPasajeroPerfil"></div>

        <button class="btn-accion" onclick="mostrarPanel('panelEditarDatos')">Editar datos</button>
        <button class="btn-accion" onclick="mostrarPanel('panelContrasena')">Cambiar contraseña</button>
        <button class="btn-accion" onclick="mostrarPanel('panelFoto')">Cambiar fotografía</button>

        <!-- Paneles -->
        <div id="panelEditarDatos" class="panel-oculto">
            <span class="cerrar" onclick="ocultarPanel('panelEditarDatos')">&times;</span>

            <form id="formEditarPasajero">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="text" name="apellido" placeholder="Apellido" required>
                <input type="text" name="telefono" placeholder="Teléfono" required>
                <button type="submit" class="btn-accion">Actualizar datos</button>
            </form>

        </div>

        <div id="panelContrasena" class="panel-oculto">
            <span class="cerrar" onclick="ocultarPanel('panelContrasena')">&times;</span>
            <form id="formContrasenaPasajero">
                <input type="password" name="actual" placeholder="Contraseña actual" required>
                <input type="password" name="nueva" placeholder="Nueva contraseña" required>
                <input type="password" name="confirmar" placeholder="Confirmar nueva contraseña" required>
                <button type="submit" class="btn-accion">Actualizar contraseña</button>
            </form>
        </div>

        <div id="panelFoto" class="panel-oculto">
            <span class="cerrar" onclick="ocultarPanel('panelFoto')">&times;</span>
            <form id="formFotoPasajero">
                <input type="file" name="foto" accept="image/*" required onchange="vistaPreviaFotoPasajero(event)">
                <img id="previewFotoPasajero" style="max-width:150px; margin-top:10px; display:none;">
                <button type="submit" class="btn-accion">Actualizar fotografía</button>
            </form>
        </div>

    </section>
</main>

<script>
function cerrarSesion() {
    localStorage.removeItem("usuario");
    window.location.href = "/login";
}
</script>

</body>
</html>
