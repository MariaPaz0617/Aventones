<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrador - Rides App</title>

    {{-- Si usas Vite --}}
    {{-- @vite('resources/css/menu.css') --}}

    {{-- Si usas carpeta public/css --}}
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>

<body>

<header id="headerAdmin" class="header">
    <div id="perfilAdmin" class="perfil-chofer"></div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" id="btnCerrarSesion" class="btn-salir">Cerrar sesión</button>
    </form>
</header>

<main>
    <!-- Sección: Gestión de Usuarios -->
    <section class="seccion">
        <h2>Gestión de Usuarios</h2>

        <table id="tablaUsuarios" class="tabla">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
</main>

<script>
/* ==========================
   CARGAR PERFIL Y VALIDAR LOGIN
   ========================== */

document.addEventListener("DOMContentLoaded", () => {

    const usuario = JSON.parse(localStorage.getItem("usuario"));

    if (!usuario || usuario.rol !== "administrativo") {
        window.location.href = "{{ route('login') }}";
        return;
    }

    cargarPerfilAdmin();
    cargarUsuarios();

    document.getElementById("btnCerrarSesion")?.addEventListener("click", () => {
        localStorage.removeItem("usuario");
        window.location.href = "{{ url('/') }}";
    });
});

/* ==========================
   PERFIL ADMIN
   ========================== */

function cargarPerfilAdmin() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const contenedor = document.getElementById("perfilAdmin");

    contenedor.innerHTML = `
        <img src="{{ asset('img/usuarios') }}/${usuario.foto || 'default.jpg'}"
             alt="Foto de perfil"
             style="width:70px; border-radius:50%; object-fit:cover;">
        <div>
            <h3>${usuario.nombre} ${usuario.apellido}</h3>
            <p>${usuario.email}</p>
        </div>
    `;
}

/* ==========================
   CARGAR USUARIOS
   ========================== */

async function cargarUsuarios() {
    const response = await fetch("{{ url('php/obtener_usuarios.php') }}");
    const data = await response.json();
    const tbody = document.querySelector("#tablaUsuarios tbody");

    if (data.success && data.usuarios.length > 0) {
        tbody.innerHTML = "";

        data.usuarios.forEach(u => {
            const esActivo = u.estado === "ACTIVA";
            const fila = document.createElement("tr");

            fila.innerHTML = `
                <td>${u.nombre}</td>
                <td>${u.email}</td>
                <td>${u.rol}</td>
                <td>${esActivo ? "Activa" : "Inactiva"}</td>
                <td>
                    ${u.rol_id !== 1
                        ? `<button onclick="${esActivo ? `desactivarUsuario(${u.id})` : `activarUsuario(${u.id})`}">
                               ${esActivo ? "Desactivar" : "Activar"}
                           </button>`
                        : "<em>No editable</em>"}
                </td>
            `;

            tbody.appendChild(fila);
        });

    } else {
        tbody.innerHTML = `
            <tr><td colspan="5"><em>No hay usuarios registrados.</em></td></tr>
        `;
    }
}

/* ==========================
   DESACTIVAR USUARIO
   ========================== */

async function desactivarUsuario(id) {
    if (!confirm("¿Deseas desactivar este usuario?")) return;

    const response = await fetch("{{ url('php/desactivar_usuario.php') }}", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    });

    const data = await response.json();
    alert(data.message);
    if (data.success) cargarUsuarios();
}

/* ==========================
   ACTIVAR USUARIO
   ========================== */

async function activarUsuario(id) {
    if (!confirm("¿Deseas activar este usuario?")) return;

    const response = await fetch("{{ url('php/activar_usuario.php') }}", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    });

    const data = await response.json();
    alert(data.message);
    if (data.success) cargarUsuarios();
}

</script>

</body>
</html>
