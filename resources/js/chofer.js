console.log("chofer.js cargado correctamente");

/* ============================================================
   FUNCIÓN GLOBAL PARA ARMAR LA RUTA DE LA FOTO
============================================================ */
function rutaFoto(fotoBD) {
    // Si existe foto, se sirve desde storage/usuarios
    if (fotoBD) {
        return `/storage/${fotoBD}`;
    }

    // Si NO existe, usar imagen por defecto
    return "/img/usuarios/default.jpg";
}

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
    cargarVehiculos();
    //cargarRidesChofer(); // pendiente
    //CARGAR RESRVAS PENDIENTES - pendiente
});

/* ============================================================
   2. MOSTRAR PERFIL EN EL ENCABEZADO
============================================================ */
function cargarPerfilChofer() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const contenedor = document.getElementById("perfilChofer");

    contenedor.innerHTML = `
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


/* ============================================================
   8. ACTUALIZAR FOTOGRAFÍA DEL CHOFER
============================================================ */
document.getElementById("formFotoChofer").addEventListener("submit", actualizarFotoChofer);

async function actualizarFotoChofer(e) {
    e.preventDefault();

    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const foto = document.querySelector("#formFotoChofer [name=foto]").files[0];

    if (!foto) {
        alert("Selecciona una fotografía.");
        return;
    }

    const formData = new FormData();
    formData.append("id", usuario.id);
    formData.append("foto", foto);

    try {
        const response = await fetch("/chofer/actualizar-foto", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            alert("Fotografía actualizada correctamente.");

            // Actualizar localStorage
            usuario.foto = data.foto;
            localStorage.setItem("usuario", JSON.stringify(usuario));

            // Actualizar perfil visual
            cargarPerfilChofer();
            llenarPerfilEnSeccionPrincipal();

            ocultarPanel("panelFoto");
        } else {
            alert(data.message);
        }

    } catch (error) {
        console.error("Error al actualizar foto:", error);
        alert("Ocurrió un error inesperado al actualizar la fotografía.");
    }
}


/* ============================================================
   9. ACTUALIZAR CONTRASEÑA DEL CHOFER
============================================================ */
document.getElementById("formContrasena").addEventListener("submit", actualizarContrasena);

async function actualizarContrasena(e) {
    e.preventDefault();

    const usuario = JSON.parse(localStorage.getItem("usuario"));

    const actual = document.querySelector("#formContrasena [name=actual]").value;
    const nueva = document.querySelector("#formContrasena [name=nueva]").value;
    const confirmar = document.querySelector("#formContrasena [name=confirmar]").value;

    // Crear FormData porque no usamos JSON para contraseñas
    const formData = new FormData();
    formData.append("id", usuario.id);
    formData.append("actual", actual);
    formData.append("nueva", nueva);
    formData.append("confirmar", confirmar);

    try {
        const response = await fetch("/chofer/actualizar-contrasena", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            alert("Contraseña cambiada correctamente. Debes iniciar sesión nuevamente.");

            // cerrar sesión automáticamente
            localStorage.removeItem("usuario");
            window.location.href = "/login";
            return;
        }

        alert(data.message);

    } catch (e) {
        console.error("Error al actualizar contraseña:", e);
        alert("Ocurrió un error inesperado.");
    }
}




//CRUD DE VEHÍCULOS
async function registrarVehiculo(e) {
    e.preventDefault();

    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const form = document.getElementById("formVehiculo");
    const formData = new FormData(form);

    formData.append("usuario_id", usuario.id);

    const response = await fetch("/vehiculos/registrar", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content },
        body: formData
    });

    const data = await response.json();
    alert(data.message);

    if (data.success) {
        cerrarModal();
        cargarVehiculos();
    }
}




//FUNCIÓN PARA CARGAR VEHÍCULOS. BUSCA VEHÍCULOS ASOCIADOS AL CHOFER LOGUEADO
async function cargarVehiculos() {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    const contenedor = document.getElementById("listaVehiculos");

    const response = await fetch("/vehiculos/listar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: JSON.stringify({ usuario_id: usuario.id })
    });

    const data = await response.json();
    contenedor.innerHTML = "";

    if (!data.success || data.vehiculos.length === 0) {
        contenedor.innerHTML = `<p class='sin-vehiculos'>No tienes vehículos registrados.</p>`;
        return;
    }

    data.vehiculos.forEach(v => {
        const tarjeta = document.createElement("div");
        tarjeta.className = "vehiculo-card";

        const ruta = v.foto ? `/storage/${v.foto}` : "/img/vehiculos/default.jpg";

        tarjeta.innerHTML = `
            <img class="vehiculo-foto" src="${ruta}" alt="foto vehiculo">

            <div class="vehiculo-info">
                <p><strong>Placa:</strong> ${v.placa}</p>
                <p><strong>Marca:</strong> ${v.marca}</p>
                <p><strong>Modelo:</strong> ${v.modelo}</p>
                <p><strong>Año:</strong> ${v.año}</p>
                <p><strong>Color:</strong> ${v.color}</p>
                <p><strong>Asientos:</strong> ${v.capacidad_asientos}</p>
            </div>

            <div class="vehiculo-acciones">
                <button class="btn-editar" onclick='prepararEdicionVehiculo(${JSON.stringify(v)})'>Editar</button>
                <button class="btn-eliminar" onclick="eliminarVehiculo(${v.id})">Eliminar</button>
            </div>
        `;

        contenedor.appendChild(tarjeta);
    });
}


// Cargar vehículos al iniciar
document.getElementById("formVehiculo").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = document.getElementById("formVehiculo");

    if (form.dataset.editar) {
        actualizarVehiculo(e);  // 🔥 modo edición
    } else {
        registrarVehiculo(e);   // 🔥 modo registro
    }
});



// Preparar formulario para edición
function prepararEdicionVehiculo(v) {

    abrirModal();

    const form = document.getElementById("formVehiculo");

    form.dataset.editar = v.id;
    form.dataset.colorOriginal = v.color;
    form.dataset.fotoActual = v.foto;

    form.placa.value = v.placa;
    form.color.value = v.color;
    form.marca.value = v.marca;
    form.modelo.value = v.modelo;
    form.año.value = v.año;
    form.capacidad_asientos.value = v.capacidad_asientos;

    document.getElementById("tituloModalVehiculo").textContent = "Editar vehículo";
    document.getElementById("botonModalVehiculo").textContent = "Actualizar vehículo";

    const inputFoto = form.querySelector("input[name='foto']");

    // Foto NO obligatoria por defecto
    inputFoto.required = false;

    // Si cambia color → foto obligatoria
    form.color.oninput = () => {
        const colorModificado = form.color.value.trim() !== form.dataset.colorOriginal;
        inputFoto.required = colorModificado;
    };
}


//fUNCIÓN PARA ACTUALIZAR VEHÍCULO
async function actualizarVehiculo(e) {
    e.preventDefault();
    // Obtener datos del formulario
    const form = document.getElementById("formVehiculo");
    const id = form.dataset.editar;
    // Crear FormData
    const formData = new FormData(form);
    formData.append("id", id);

    const response = await fetch("/vehiculo/editar", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: formData
    });
    // Procesar respuesta
    const data = await response.json();
    alert(data.message);

    if (data.success) {
        cerrarModal();
        cargarVehiculos();
    }
}

//FUNCIÓN PARA ELIMINAR VEHÍCULO
async function eliminarVehiculo(id) {
    if (!confirm("¿Deseas eliminar este vehículo?")) return;
    // Enviar solicitud de eliminación
    const response = await fetch("/vehiculo/eliminar", {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: JSON.stringify({ id })
    });
    // Procesar respuesta
    const data = await response.json();
    alert(data.message);

    if (data.success) cargarVehiculos();
}


/* ============================================================
   10. CONTROL DEL MODAL DE VEHÍCULOS
============================================================ */

// Abrir modal para registrar o editar vehículo
function abrirModal() {
    const modal = document.getElementById("modalVehiculo");
    const form = document.getElementById("formVehiculo");

    form.reset();
    delete form.dataset.editar;
    delete form.dataset.fotoActual;
    delete form.dataset.colorOriginal;

    document.getElementById("tituloModalVehiculo").textContent = "Registrar nuevo vehículo";
    document.getElementById("botonModalVehiculo").textContent = "Guardar vehículo";

    modal.style.display = "block";
}

function cerrarModal() {
    const modal = document.getElementById("modalVehiculo");
    const form = document.getElementById("formVehiculo");

    form.reset();
    delete form.dataset.editar;
    delete form.dataset.fotoActual;
    delete form.dataset.colorOriginal;

    modal.style.display = "none";
}

// Hacerlas accesibles desde HTML
window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;

// Cerrar modal si se hace clic fuera
window.onclick = function(e) {
    const modal = document.getElementById("modalVehiculo");
    if (e.target === modal) {
        cerrarModal();
    }
};

// Exponer funciones al HTML para su uso en los botones
window.cerrarSesion = cerrarSesion;
window.registrarVehiculo = registrarVehiculo;
window.prepararEdicionVehiculo = prepararEdicionVehiculo;
window.eliminarVehiculo = eliminarVehiculo;
window.actualizarVehiculo = actualizarVehiculo;
window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;

