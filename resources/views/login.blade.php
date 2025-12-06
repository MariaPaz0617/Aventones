<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Rides App</title>

    {{-- Cargar CSS --}}
    @vite('resources/css/login.css')
    
</head>
<body>

<div class="container">
    <h2>Iniciar Sesión</h2>

    {{-- FORMULARIO --}}
    {{-- No enviamos directamente, se maneja con JS --}}
    <form id="loginForm">
        <input type="email" id="email" placeholder="Correo electrónico" required />
        <input type="password" id="password" placeholder="Contraseña" required />
    </form>

    {{-- BOTONES --}}
    <button class="link-btn" id="btnIngresar">Ingresar</button>
    <button class="link-btn" id="btnCrearCuenta">Crear cuenta</button>
</div>

<script>
document.getElementById("btnCrearCuenta").addEventListener("click", function() {
    window.location.href = "{{ route('register.view') }}";
});

document.getElementById("btnIngresar").addEventListener("click", async function() {
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!email || !password) {
        alert("Por favor ingresa tu correo y contraseña.");
        return;
    }

    const response = await fetch("{{ route('login.api') }}", {
        method: "POST",
        headers: { 
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}" 
        },
        body: JSON.stringify({ email, password })
    });

    const data = await response.json();

    if (data.success) {
        // Guardar usuario en LocalStorage
        localStorage.setItem("usuario", JSON.stringify(data));

        if (data.rol === "chofer") {
            window.location.href = "{{ route('menu.chofer') }}";
        } else if (data.rol === "pasajero") {
            window.location.href = "{{ route('menu.pasajero') }}";
        } else if (data.rol === "administrativo") {
            window.location.href = "{{ route('menu.admin') }}";
        }
    } else {
        alert(data.message);
    }
});
</script>

</body>
</html>
