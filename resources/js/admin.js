console.log("admin.js cargado correctamente");

document.addEventListener("DOMContentLoaded", () => {
    cargarUsuarios();
});

async function cargarUsuarios() {
    const rol = document.getElementById("filtroTipo").value;

    try {
        const response = await fetch(`/admin/usuarios?tipo=${rol}`);
        const data = await response.json();

        const tbody = document.getElementById("tablaUsuarios");
        if (!tbody) {
            console.error("No se encontró #tablaUsuarios en el DOM");
            return;
        }

        tbody.innerHTML = "";

        data.usuarios.forEach(u => {
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

async function cambiarEstado(id, activar) {
    const url = activar ? "/admin/activar" : "/admin/desactivar";

    const response = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
        },
        body: JSON.stringify({ id })
    });

    const data = await response.json();
    alert(data.message);
    cargarUsuarios();
}

// 👉 EXPOSE TO GLOBAL SCOPE
window.cargarUsuarios = cargarUsuarios;
window.cambiarEstado = cambiarEstado;