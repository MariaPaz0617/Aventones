<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear cuenta</title>

    {{-- Cargar CSS con Vite --}}
    @vite('resources/css/cuenta.css')
</head>

<body>

<div class="form-container">
    <h2>Crear cuenta</h2>

    <form id="crearCuentaForm" enctype="multipart/form-data">

        <input type="text" id="nombre" placeholder="Nombre" required>
        <input type="text" id="apellido" placeholder="Apellido" required>
        <input type="text" id="cedula" placeholder="Número de cédula" required>
        <input type="date" id="fecha_nacimiento" required>

        <input type="email" id="email" placeholder="Correo electrónico" required>
        <input type="tel" id="telefono" placeholder="Número de teléfono" required>

        <input type="file" id="foto" accept="image/*" required>

        <input type="password" id="password" placeholder="Contraseña" required>
        <input type="password" id="password2" placeholder="Repetir contraseña" required>

        <select id="rol" required>
            <option value="">Seleccione un rol</option>
            <option value="chofer">Chofer</option>
            <option value="pasajero">Pasajero</option>
        </select>

        <button type="submit" id="btnCrear">Crear</button>
        <button type="button" id="btnCancelar">Cancelar</button>
    </form>
</div>

<script>
// BOTÓN CANCELAR → ir a login
document.getElementById("btnCancelar").addEventListener("click", () => {
    window.location.href = "{{ route('login.view') }}";  
});

// ENVÍO DEL FORMULARIO
document.getElementById("crearCuentaForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const apellido = document.getElementById("apellido").value.trim();
    const cedula = document.getElementById("cedula").value.trim();
    const fecha_nacimiento = document.getElementById("fecha_nacimiento").value;

    const email = document.getElementById("email").value.trim();
    const telefono = document.getElementById("telefono").value.trim();

    const password = document.getElementById("password").value.trim();
    const password2 = document.getElementById("password2").value.trim();

    const rol = document.getElementById("rol").value;
    const foto = document.getElementById("foto").files[0];

    if (!nombre || !apellido || !cedula || !fecha_nacimiento || !email || !telefono || !password || !password2 || !rol || !foto) {
        alert("Por favor completa todos los campos.");
        return;
    }

    if (password !== password2) {
        alert("Las contraseñas no coinciden.");
        return;
    }

    const formData = new FormData();
    formData.append("nombre", nombre);
    formData.append("apellido", apellido);
    formData.append("cedula", cedula);
    formData.append("fecha_nacimiento", fecha_nacimiento);
    formData.append("email", email);
    formData.append("telefono", telefono);
    formData.append("password", password);
    formData.append("rol", rol);
    formData.append("foto", foto);

    try {
        const response = await fetch("{{ route('register.api') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: formData
        });
        console.log("response", response);
        const text = await response.text();
        console.log("Respuesta del servidor:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            alert("Error interpretando respuesta del servidor.");
            return;
        }

        if (data.success) {
            alert("Revisa tu correo para activar tu cuenta.");
            window.location.href = "{{ route('login.view') }}";
        }

    } catch (error) {
        console.error("Error al crear la cuenta:", error);
        alert("Ocurrió un error al crear la cuenta.");
    }
});
</script>

</body>
</html>
