import './bootstrap';

export function cerrarSesion() {
    localStorage.removeItem("usuario");
    window.location.href = "/login";
}

export function validarSesion(rolEsperado) {
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    if (!usuario || usuario.rol !== rolEsperado) {
        cerrarSesion();
    }
    return usuario;
}

// Exponer funciones al HTML
window.cerrarSesion = cerrarSesion;
window.validarSesion = validarSesion;
