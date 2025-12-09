<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rides Disponibles - RidesApp</title>

    {{-- Carga estilos y scripts generales --}}
    @vite('resources/css/menu.css')
    @vite('resources/js/home.js')
</head>

<body>

<header class="header">
    <h2>RidesApp</h2>

    <button onclick="window.location.href='/login'" class="btn-salir">
        Iniciar sesión
    </button>
</header>

<main class="seccion">

    <h2>Rides Disponibles</h2>

    <!-- FILTROS DE BÚSQUEDA -->
    <div class="filtros-busqueda">

        <input 
            type="text" 
            id="origen"
            placeholder="Lugar de salida"
        >

        <input 
            type="text"
            id="destino"
            placeholder="Lugar de llegada"
        >

        <button onclick="filtrarRidesPublicos()">
            Buscar
        </button>

        <select id="ordenarPor" onchange="filtrarRidesPublicos()">
            <option value="fecha">Fecha</option>
            <option value="lugar_salida">Lugar de salida</option>
            <option value="lugar_llegada">Lugar de llegada</option>
        </select>

        <select id="ordenDireccion" onchange="filtrarRidesPublicos()">
            <option value="asc">Ascendente</option>
            <option value="desc">Descendente</option>
        </select>
    </div>

    <!-- CONTENEDOR DE TABLA -->
    <div id="tablaRidesPublicos" style="margin-top: 20px;">
        <p>Cargando rides...</p>
    </div>

    <p style="margin-top: 20px; color: gray;">
        Para reservar un ride debe iniciar sesión como pasajero.
    </p>

</main>

</body>
</html>
