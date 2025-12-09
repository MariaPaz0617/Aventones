<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administrador - Aventones</title>
    @vite('resources/js/app.js')
    @vite('resources/css/menu.css')
    @vite('resources/js/admin.js')
</head>
<body>

<header id="headerAdmin" class="header">
    <div id="perfilAdmin" class="perfil-admin"></div>

    <button onclick="cerrarSesion()" class="btn-salir">Cerrar sesión</button>
</header>

<main>
    <section class="seccion">
        <h2>Gestión de Usuarios</h2>

        <!-- FILTRO POR ROL -->
        <label>Filtrar por tipo:</label>
        <select id="filtroTipo">
            <option value="chofer">Chofer</option>
            <option value="pasajero">Pasajero</option>
        </select>

        <button class="btn-accion" onclick="cargarUsuarios()">Aplicar filtro</button>

        <!-- Tabla -->
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
</main>

</body>
</html>
