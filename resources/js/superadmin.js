console.log("superadmin.js cargado correctamente");

/* ===============================
   Cargar usuarios por tipo
================================= */
async function cargarUsuarios() {
    console.log("-> cargarUsuarios() ejecutado");

    const rol = document.getElementById("filtroTipo").value;
    console.log("Rol seleccionado:", rol);

    try {
        const response = await fetch(`/superadmin/usuarios?tipo=${rol}`);
        const data = await response.json();
        console.log("Usuarios recibidos:", data);

        const usuarios = data.usuarios;
        const tbody = document.getElementById("tablaUsuarios");
        tbody.innerHTML = "";

        usuarios.forEach(u => {
            const boton = u.estado === "ACTIVO"
                ? `<button onclick="cambiarEstado(${u.id}, false)">Desactivar</button>`
                : `<button onclick="cambiarEstado(${u.id}, true)">Activar</button>`;

            tbody.innerHTML += `
                <tr>
                    <td>${u.nombre} ${u.apellido}</td>
                    <td>${u.email}</td>
                    <td>${u.rol}</td>
                    <td>${u.estado}</td>
                    <td>${boton}</td>
                </tr>
            `;
        });

    } catch (e) {
        console.error("Error en cargarUsuarios:", e);
    }
}

/* ===============================
    Cambiar estado usuario
================================= */
async function cambiarEstado(id, activar) {
    const url = activar ? "/superadmin/activar" : "/superadmin/desactivar";

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
            },
            body: JSON.stringify({ id })
        });

        const data = await response.json();
        console.log(data);

        alert(data.message);
        cargarUsuarios(); // recargar tabla

    } catch (e) {
        console.error("Error en cambiarEstado:", e);
    }
}

window.cambiarEstado = cambiarEstado;


/* ===============================
   Crear administrador
================================= */
async function crearAdmin() {
    console.log("-> crearAdmin() ejecutado");

    const nombre = document.getElementById("newNombre").value;
    const apellido = document.getElementById("newApellido").value;
    const email = document.getElementById("newEmail").value;
    const password = document.getElementById("newPassword").value;

    console.log("Datos a enviar:", { nombre, apellido, email, password });

    try {
        const response = await fetch("/superadmin/crear-admin", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
            },
            body: JSON.stringify({ nombre, apellido, email, password })
        });

        console.log("Respuesta recibida:", response);

        const data = await response.json();
        console.log("JSON recibido:", data);

        if (data.success) {
            alert("Administrador creado correctamente");
            cargarUsuarios();
        } else {
            alert("Error: " + data.message);
        }

    } catch (e) {
        console.error("Error inesperado en crearAdmin:", e);
    }
}

/* ===============================
   Activar usuario
================================= */
async function activar(id) {
    console.log("-> activar() ejecutado para:", id);

    await fetch("/superadmin/activar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: JSON.stringify({ id })
    });

    cargarUsuarios();
}

/* ===============================
   Desactivar usuario
================================= */
async function desactivar(id) {
    console.log("-> desactivar() ejecutado para:", id);

    await fetch("/superadmin/desactivar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: JSON.stringify({ id })
    });

    cargarUsuarios();
}

window.crearAdmin = crearAdmin;
window.cargarUsuarios = cargarUsuarios;
window.activar = activar;
window.desactivar = desactivar;
