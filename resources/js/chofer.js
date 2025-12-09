console.log("chofer.js cargado correctamente");

/* ============================================================
   1. INICIALIZACIÓN
============================================================ */
document.addEventListener("DOMContentLoaded", () => {

    const usuario = JSON.parse(localStorage.getItem("usuario"));

    if (!usuario || usuario.rol !== "chofer") {
        window.location.href = "/login";
        return;
    }

    cargarPerfilChofer();
    llenarPerfilEnSeccionPrincipal();
});

/* ============================================================
   2. MOSTRAR PERFIL EN EL ENCABEZADO
============================================================ */
function cargarPerfilChofer() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const contenedor = document.getElementById("perfilChofer");

    contenedor.innerHTML = `
        <img src="/img/${usuario.foto || 'usuarios/default.jpg'}"
             alt="Foto de perfil"
             style="width:70px; border-radius:50%; object-fit:cover;">
        <div>
            <h3>${usuario.nombre} ${usuario.apellido}</h3>
            <p>${usuario.email}</p>
        </div>
    `;
}

/* ============================================================
   3. MOSTRAR PERFIL EN LA SECCIÓN "Mi Perfil"
============================================================ */
function llenarPerfilEnSeccionPrincipal() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const cont = document.getElementById("perfilChoferPerfil");

    cont.innerHTML = `
        <p><strong>Nombre:</strong> ${usuario.nombre}</p>
        <p><strong>Apellido:</strong> ${usuario.apellido}</p>
        <p><strong>Correo:</strong> ${usuario.email}</p>
        <p><strong>Teléfono:</strong> ${usuario.telefono || 'No registrado'}</p>
        <img src="/img/${usuario.foto || 'usuarios/default.jpg'}"
             style="width:120px; border-radius:10px; margin-top:10px;">
    `;
}

/* ============================================================
   4. MOSTRAR Y OCULTAR PANELES
============================================================ */
function mostrarPanel(id) {
    document.getElementById(id).classList.remove("panel-oculto");
}

function ocultarPanel(id) {
    document.getElementById(id).classList.add("panel-oculto");
}

window.mostrarPanel = mostrarPanel;
window.ocultarPanel = ocultarPanel;

/* ============================================================
   5. ACTUALIZAR PERFIL DEL CHOFER
============================================================ */

document.getElementById("formEditarChofer").addEventListener("submit", actualizarDatosChofer);

function actualizarDatosChofer(e) {
    e.preventDefault();

    const usuario = JSON.parse(localStorage.getItem("usuario"));

    const datos = {
        id: usuario.id,
        nombre: document.querySelector("#formEditarChofer [name=nombre]").value,
        apellido: document.querySelector("#formEditarChofer [name=apellido]").value,
        telefono: document.querySelector("#formEditarChofer [name=telefono]").value
    };

    enviarActualizacionChofer(datos);
}

async function enviarActualizacionChofer(datos) {
    try {
        const response = await fetch("/chofer/editar", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
            },
            body: JSON.stringify(datos)
        });

        const data = await response.json();

        if (data.success) {
            alert("Datos actualizados correctamente.");

            // Actualizar localStorage
            const usuario = JSON.parse(localStorage.getItem("usuario"));
            usuario.nombre = datos.nombre;
            usuario.apellido = datos.apellido;
            usuario.telefono = datos.telefono;
            localStorage.setItem("usuario", JSON.stringify(usuario));

            // Actualizar vista
            cargarPerfilChofer();
            llenarPerfilEnSeccionPrincipal();
            ocultarPanel("panelEditarDatos");

        } else {
            alert(data.message);
        }

    } catch (err) {
        console.error("Error al actualizar datos:", err);
        alert("Ocurrió un error inesperado.");
    }
}

/* ============================================================
   6. VISTA PREVIA DE FOTO
============================================================ */
function vistaPreviaFotoChofer(event) {
    const img = document.getElementById("previewFotoChofer");
    img.src = URL.createObjectURL(event.target.files[0]);
    img.style.display = "block";
}

window.vistaPreviaFotoChofer = vistaPreviaFotoChofer;

/* ============================================================
   7. CERRAR SESIÓN
============================================================ */
function cerrarSesion() {
    localStorage.removeItem("usuario");
    window.location.href = "/login";
}

window.cerrarSesion = cerrarSesion;
