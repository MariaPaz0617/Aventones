console.log("home.js cargado correctamente");

/* ===============================================
   CARGAR RIDES AL INICIAR
================================================ */
document.addEventListener("DOMContentLoaded", () => {
    cargarRidesPublicos();
});

/* ===============================================
   LLAMADA AL BACKEND
================================================ */
async function cargarRidesPublicos() {

    const origen = document.getElementById("origen").value;
    const destino = document.getElementById("destino").value;
    const ordenarPor = document.getElementById("ordenarPor").value;
    const direccion = document.getElementById("ordenDireccion").value;

    //CARGA LOS RIDES DESDE EL BACKEND USANDO AWAIT/FETCH
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

function filtrarRidesPublicos() {
    cargarRidesPublicos();
}

/* ===============================================
   RENDERIZAR TABLA
================================================ */
function mostrarRidesPublicos(rides) {

    const cont = document.getElementById("tablaRidesPublicos");
    cont.innerHTML = "";

    if (!rides.length) {
        cont.innerHTML = "<p>No se encontraron rides disponibles.</p>";
        return;
    }

    let html = `
        <table class="tabla">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Salida</th>
                    <th>Llegada</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Disponibles</th>
                </tr>
            </thead>
            <tbody>
    `;

    rides.forEach(r => {
        html += `
            <tr class="${r.espacios_disponibles == 0 ? 'fila-inactiva' : ''}">
                <td>${r.nombre}</td>
                <td>${r.lugar_salida}</td>
                <td>${r.lugar_llegada}</td>
                <td>${r.fecha}</td>
                <td>${r.hora}</td>
                <td>${r.espacios_disponibles}</td>
            </tr>
        `;
    });

    html += `
            </tbody>
        </table>
    `;

    cont.innerHTML = html;
}
