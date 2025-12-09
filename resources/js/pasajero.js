console.log("pasajero.js cargado correctamente");

/* ============================================================
   FUNCIÓN GLOBAL PARA ARMAR LA RUTA DE LA FOTO
============================================================ */
function rutaFoto(fotoBD) {
    return fotoBD ? `/storage/${fotoBD}` : "/img/usuarios/default.jpg";
}

/* ============================================================
   1. INICIALIZACIÓN DEL MENÚ PASAJERO
============================================================ */
document.addEventListener("DOMContentLoaded", () => {
    const usuario = JSON.parse(localStorage.getItem("usuario"));

    if (!usuario || usuario.rol !== "pasajero") {
        window.location.href = "/login";
        return;
    }

    cargarPerfilPasajero();
    llenarPerfilEnSeccionPrincipal();
    cargarRidesPublicos();
    cargarMisReservas();
});

/* ============================================================
   2. PERFIL EN EL ENCABEZADO
============================================================ */
function cargarPerfilPasajero() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const cont = document.getElementById("perfilPasajero");

    cont.innerHTML = `
        <img src="${rutaFoto(usuario.foto)}"
             alt="Foto de perfil"
             style="width:70px; border-radius:50%; object-fit:cover;">
        <div>
            <h3>${usuario.nombre} ${usuario.apellido}</h3>
            <p>${usuario.email}</p>
        </div>
    `;
}

/* ============================================================
   3. PERFIL EN LA SECCIÓN "MI PERFIL"
============================================================ */
function llenarPerfilEnSeccionPrincipal() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const cont = document.getElementById("perfilPasajeroPerfil");

    cont.innerHTML = `
        <p><strong>Nombre:</strong> ${usuario.nombre}</p>
        <p><strong>Apellido:</strong> ${usuario.apellido}</p>
        <p><strong>Correo:</strong> ${usuario.email}</p>
        <p><strong>Teléfono:</strong> ${usuario.telefono || "No registrado"}</p>

        <img src="${rutaFoto(usuario.foto)}"
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
   5. ACTUALIZAR PERFIL DEL PASAJERO
============================================================ */

document.getElementById("formEditarPasajero")
        .addEventListener("submit", actualizarDatosPasajero);

function actualizarDatosPasajero(e) {
    e.preventDefault();

    const usuario = JSON.parse(localStorage.getItem("usuario"));

    const datos = {
        id: usuario.id,
        nombre: document.querySelector("#formEditarPasajero [name=nombre]").value,
        apellido: document.querySelector("#formEditarPasajero [name=apellido]").value,
        telefono: document.querySelector("#formEditarPasajero [name=telefono]").value
    };

    enviarActualizacionPasajero(datos);
}

async function enviarActualizacionPasajero(datos) {

    try {
        const response = await fetch("/pasajero/editar", {
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

            // actualizar localStorage
            const usuario = JSON.parse(localStorage.getItem("usuario"));
            usuario.nombre = datos.nombre;
            usuario.apellido = datos.apellido;
            usuario.telefono = datos.telefono;
            localStorage.setItem("usuario", JSON.stringify(usuario));

            // actualizar UI
            cargarPerfilPasajero();
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
function vistaPreviaFotoPasajero(event) {
    const img = document.getElementById("previewFotoPasajero");
    img.src = URL.createObjectURL(event.target.files[0]);
    img.style.display = "block";
}

window.vistaPreviaFotoPasajero = vistaPreviaFotoPasajero;

/* ============================================================
   7. ACTUALIZAR FOTOGRAFÍA
============================================================ */
document.getElementById("formFotoPasajero")
    .addEventListener("submit", actualizarFotoPasajero);

async function actualizarFotoPasajero(e) {
    e.preventDefault();

    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const foto = document.querySelector("#formFotoPasajero [name=foto]").files[0];

    if (!foto) {
        alert("Selecciona una fotografía.");
        return;
    }

    const formData = new FormData();
    formData.append("id", usuario.id);
    formData.append("foto", foto);

    const response = await fetch("/pasajero/actualizar-foto", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: formData
    });

    const data = await response.json();

    if (data.success) {
        alert("Fotografía actualizada correctamente.");

        usuario.foto = data.foto;
        localStorage.setItem("usuario", JSON.stringify(usuario));

        cargarPerfilPasajero();
        llenarPerfilEnSeccionPrincipal();
        ocultarPanel("panelFoto");
    } else {
        alert(data.message);
    }
}

/* ============================================================
   8. ACTUALIZAR CONTRASEÑA (USA EL MISMO CONTROLADOR)
============================================================ */
document.getElementById("formContrasenaPasajero")
    .addEventListener("submit", actualizarContrasenaPasajero);

async function actualizarContrasenaPasajero(e) {
    e.preventDefault();

    const usuario = JSON.parse(localStorage.getItem("usuario"));

    const formData = new FormData();
    formData.append("id", usuario.id);
    formData.append("actual", document.querySelector("#formContrasenaPasajero [name=actual]").value);
    formData.append("nueva", document.querySelector("#formContrasenaPasajero [name=nueva]").value);
    formData.append("confirmar", document.querySelector("#formContrasenaPasajero [name=confirmar]").value);

    const response = await fetch("/pasajero/actualizar-contrasena", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: formData
    });

    const data = await response.json();

    if (data.success) {
        alert("Contraseña actualizada correctamente. Debes iniciar sesión nuevamente.");
        localStorage.removeItem("usuario");
        window.location.href = "/login";
    } else {
        alert(data.message);
    }
}


async function cargarRidesPublicos() {

    const origen = document.getElementById("origen").value;
    const destino = document.getElementById("destino").value;
    const ordenarPor = document.getElementById("ordenarPor").value;
    const direccion = document.getElementById("ordenDireccion").value;

    const response = await fetch("/rides/publicos", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: JSON.stringify({
            origen,
            destino,
            ordenar_por: ordenarPor,
            direccion
        })
    });

    const data = await response.json();
    mostrarRidesPublicos(data.rides);
}

function filtrarRides() {
    cargarRidesPublicos();
}


function mostrarRidesPublicos(rides) {

    const cont = document.getElementById("listaRidesPublicos");
    cont.innerHTML = "";

    if (!rides.length) {
        cont.innerHTML = `<p>No se encontraron rides disponibles.</p>`;
        return;
    }

    rides.forEach(r => {
        cont.innerHTML += `
            <div class="ride-card">
                <h3>${r.nombre}</h3>

                <p><strong>Salida:</strong> ${r.lugar_salida}</p>
                <p><strong>Llegada:</strong> ${r.lugar_llegada}</p>
                <p><strong>Fecha:</strong> ${r.fecha}</p>
                <p><strong>Hora:</strong> ${r.hora}</p>
                <p><strong>Disponibles:</strong> ${r.espacios_disponibles}</p>

                <input id="asientos_${r.id}"
                       type="number"
                       min="1"
                       max="${r.espacios_disponibles}"
                       placeholder="Asientos a reservar">

                <button onclick="reservarRide(${r.id})">
                    Reservar
                </button>
            </div>
        `;
    });
}


async function reservarRide(rideId) {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const asientos = document.getElementById("asientos_" + rideId).value;

    if (!asientos || asientos < 1) {
        alert("Ingrese la cantidad de asientos.");
        return;
    }

    const response = await fetch("/reservas/crear", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: JSON.stringify({
            pasajero_id: usuario.id,
            ride_id: rideId,
            cantidad_asientos: asientos
        })
    });

    const data = await response.json();
    alert(data.message);

    if (data.success) {
        cargarRidesPublicos();   // refresca lista de rides
        cargarMisReservas();     // 🔥 refresca la tabla de reservas
    }

}

async function cargarMisReservas() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));

    const response = await fetch("/reservas/mias", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: JSON.stringify({ pasajero_id: usuario.id })
    });

    const data = await response.json();
    llenarTablaReservas(data.reservas);
}

function llenarTablaReservas(reservas) {
    const tbody = document.querySelector("#tablaReservas tbody");
    tbody.innerHTML = "";

    reservas.forEach(r => {
        tbody.innerHTML += `
            <tr>
                <td>${r.ride.nombre}</td>
                <td>${r.ride.lugar_salida} → ${r.ride.lugar_llegada}</td>
                <td>${r.ride.fecha}</td>
                <td>${r.ride.hora}</td>
                <td>${r.estado}</td>
                <td>
                    ${r.estado === 'PENDIENTE'
                        ? `<button onclick="cancelarReserva(${r.id})">Cancelar</button>`
                        : ''}
                </td>
            </tr>
        `;
    });
}

async function cancelarReserva(id) {
    const response = await fetch("/reservas/cancelar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: JSON.stringify({ id })
    });

    const data = await response.json();
    alert(data.message);

    if (data.success) cargarMisReservas();
}












/* ============================================================
   9. CERRAR SESIÓN
============================================================ */
function cerrarSesion() {
    localStorage.removeItem("usuario");
    window.location.href = "/login";
}

window.cerrarSesion = cerrarSesion;
window.filtrarRides = filtrarRides;
window.reservarRide = reservarRide;
window.cancelarReserva = cancelarReserva;
window.cargarMisReservas = cargarMisReservas;
window.cargarRidesPublicos = cargarRidesPublicos;


