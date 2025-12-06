<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Rides App - Explorar Rides</title>

    {{-- Si usas public/css --}}
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">

    {{-- Si usas Vite --}}
    {{-- @vite('resources/css/menu.css') --}}
</head>

<body>
    <!-- Encabezado público -->
    <header class="header">
        <div class="perfil-chofer">Rides App</div>

        <button onclick="irALogin()" class="btn-salir">
            Iniciar sesión
        </button>
    </header>

    <main>
        <!-- Sección: Buscar Rides -->
        <section class="seccion">
            <h2>Buscar Rides</h2>

            <div class="filtros-busqueda">
                <input type="text" id="origen" placeholder="Lugar de salida" />
                <input type="text" id="destino" placeholder="Lugar de llegada" />

                <button onclick="filtrarRides()" class="btn-accion">Buscar</button>

                <select id="ordenarPor" onchange="ordenarRides()">
                    <option value="fecha">Fecha</option>
                    <option value="lugar_salida">Lugar de salida</option>
                    <option value="lugar_llegada">Lugar de llegada</option>
                </select>

                <select id="ordenDireccion" onchange="ordenarRides()">
                    <option value="asc">Ascendente</option>
                    <option value="desc">Descendente</option>
                </select>
            </div>

            <div id="listaRidesPublicos"></div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Aventones. Maria Paz Ugalde - Xavier Fernández.</p>
    </footer>

    <script>
        //===================== REDIRECCIÓN =====================//
        function irALogin() {
            window.location.href = "{{ route('login') }}";
        }

        //===================== CARGA DE RIDES =====================//
        let ridesPublicos = [];

        document.addEventListener("DOMContentLoaded", () => {
            cargarRidesPublicos();
        });

        async function cargarRidesPublicos() {
            const response = await fetch("{{ url('php/obtener_rides_publicos.php') }}");
            const data = await response.json();
            if (data.success) {
                ridesPublicos = data.rides;
                mostrarRides(ridesPublicos);
            }
        }

        function mostrarRides(lista) {
            const contenedor = document.getElementById("listaRidesPublicos");
            contenedor.innerHTML = "";

            lista.forEach(r => {
                const card = document.createElement("div");
                card.className = "ride-card";
                card.innerHTML = `
                    <strong>${r.nombre}</strong><br>
                    ${r.lugar_salida} → ${r.lugar_llegada}<br>
                    Fecha: ${r.fecha} | Hora: ${r.hora}<br>
                    Vehículo: ${r.vehiculo_marca} ${r.vehiculo_modelo} (${r.vehiculo_año})<br>
                    Espacios: ${r.cantidad_espacios} | Costo: ₡${parseFloat(r.costo).toFixed(2)}<br>
                    <em>Inicia sesión como pasajero para reservar</em>
                `;
                contenedor.appendChild(card);
            });
        }

        //===================== FILTRADO Y ORDENAMIENTO =====================//
        function filtrarRides() {
            const origen = document.getElementById("origen").value.toLowerCase();
            const destino = document.getElementById("destino").value.toLowerCase();

            const filtrados = ridesPublicos.filter(r =>
                r.lugar_salida.toLowerCase().includes(origen) &&
                r.lugar_llegada.toLowerCase().includes(destino)
            );

            mostrarRides(filtrados);
        }

        function ordenarRides() {
            const campo = document.getElementById("ordenarPor").value;
            const direccion = document.getElementById("ordenDireccion").value;

            const ordenados = [...ridesPublicos].sort((a, b) => {
                if (a[campo] < b[campo]) return direccion === "asc" ? -1 : 1;
                if (a[campo] > b[campo]) return direccion === "asc" ? 1 : -1;
                return 0;
            });

            mostrarRides(ordenados);
        }
    </script>
</body>
</html>
