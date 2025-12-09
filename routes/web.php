<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrearUsuarioController;
use App\Http\Controllers\EditarChoferController;
use App\Http\Controllers\EditarRideController;
use App\Http\Controllers\EditarVehiculoController;
use App\Http\Controllers\RegistrarVehiculoController;
use App\Http\Controllers\RegistrarRideController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EliminarRideController;
use App\Http\Controllers\EliminarVehiculoController;
use App\Http\Controllers\ActivarCuentaController;

// Vistas
Route::get('/login', function () { return view('login'); })->name('login.view');
Route::get('/registrar', function () { return view('crear_cuenta'); })->name('register.view');

//CRUD
//Registrar
Route::get('/activar-cuenta', [ActivarCuentaController::class, 'activar'])->name('activar.cuenta');
Route::post('/vehiculos/registrar', [RegistrarVehiculoController::class, 'store'])->name('vehiculos.store');
Route::post('/rides/registrar', [RegistrarRideController::class, 'store'])->name('rides.store');
//Editar
Route::post('/chofer/editar', [EditarChoferController::class, 'update'])->name('chofer.update');
Route::post('/ride/editar', [EditarRideController::class, 'update'])->name('ride.update');
Route::post('/vehiculo/editar', [EditarVehiculoController::class, 'update'])->name('vehiculo.update');
//Eliminar
Route::delete('/ride/eliminar', [EliminarRideController::class, 'delete'])->name('ride.delete');
Route::delete('/vehiculo/eliminar', [EliminarVehiculoController::class, 'delete'])->name('vehiculo.delete');



// Activar cuenta
Route::get('/activar-cuenta', [ActivarCuentaController::class, 'activar']) ->name('activar.cuenta');

// Editar chofer
Route::post('/chofer/editar', [EditarChoferController::class, 'update']) ->name('chofer.update');


// Crear vehículo
Route::post('/vehiculos/registrar', [RegistrarVehiculoController::class, 'store']) ->name('vehiculo.store');
Route::post('/vehiculo/editar', [EditarVehiculoController::class, 'update']) ->name('vehiculo.update');
Route::delete('/vehiculo/eliminar', [EliminarVehiculoController::class, 'delete'])->name('vehiculo.delete');
Route::post('/vehiculos/listar', [RegistrarVehiculoController::class, 'listar']) ->name('vehiculo.listar');

// Crear ride
Route::post('/rides/registrar', [RegistrarRideController::class, 'store']) ->name('rides.store');
Route::post('/ride/editar', [EditarRideController::class, 'update']) ->name('ride.update');
Route::delete('/ride/eliminar', [EliminarRideController::class, 'delete']) ->name('ride.delete');

// Menús según rol
Route::get('/menu/chofer', fn() => view('menu_chofer')) ->name('menu.chofer');
Route::get('/menu/pasajero', fn() => view('menu_pasajero')) ->name('menu.pasajero');
Route::get('/menu/admin', fn() => view('menu_admin')) ->name('menu.admin');
Route::get('/menu/superadmin', fn() => view('menu_superadmin')) ->name('menu.superadmin');



// API Login
Route::post('/api/login', [LoginController::class, 'login'])->name('login.api');
Route::post('/api/register', [CrearUsuarioController::class, 'store'])->name('register.api');

// SUPERADMIN PANEL
use App\Http\Controllers\SuperAdminController;

// Vista principal del menú de SuperAdministrador
Route::get('/superadmin', [SuperAdminController::class, 'index'])->name('superadmin.menu');

// Obtener usuarios filtrados por tipo (rol)
Route::get('/superadmin/usuarios', [SuperAdminController::class, 'obtenerUsuarios']);
// Crear un nuevo administrador
Route::post('/superadmin/crear-admin', [SuperAdminController::class, 'crearAdministrador']);
// Desactivar usuario
Route::post('/superadmin/desactivar', [SuperAdminController::class, 'desactivarUsuario']);
// Activar usuario
Route::post('/superadmin/activar', [SuperAdminController::class, 'activarUsuario']);


use App\Http\Controllers\AdminController;

Route::get('/admin', [AdminController::class, 'index'])->name('admin.menu');

Route::get('/admin/usuarios', [AdminController::class, 'obtenerUsuarios']);
Route::post('/admin/activar', [AdminController::class, 'activar']);
Route::post('/admin/desactivar', [AdminController::class, 'desactivar']);


// Editar chofer
Route::post('/chofer/editar', [EditarChoferController::class, 'update']);



use App\Http\Controllers\CambiarContrasenaController;

Route::post('/chofer/actualizar-contrasena', [CambiarContrasenaController::class, 'update']);



use App\Http\Controllers\ActualizarFotoChoferController;

Route::post('/chofer/actualizar-foto', [ActualizarFotoChoferController::class, 'update']);




/* ========================= RUTAS RIDES ========================= */
use App\Http\Controllers\ListarRidesController;

Route::post('/rides/listar', [ListarRidesController::class, 'listar']);


Route::post('/rides/registrar', [RegistrarRideController::class, 'store'])->name('rides.store');

Route::post('/rides/editar', [EditarRideController::class, 'update'])->name('rides.update');

Route::delete('/rides/eliminar', [EliminarRideController::class, 'delete'])->name('rides.delete');



use App\Http\Controllers\EditarPasajeroController;
use App\Http\Controllers\ActualizarFotoPasajeroController;
// PASAJERO - EDITAR DATOS
Route::post('/pasajero/editar', [EditarPasajeroController::class, 'update']);

// PASAJERO - ACTUALIZAR CONTRASEÑA
Route::post('/pasajero/actualizar-contrasena', [CambiarContrasenaController::class, 'update']);

// PASAJERO - ACTUALIZAR FOTO
Route::post('/pasajero/actualizar-foto', [ActualizarFotoPasajeroController::class, 'update']);


use App\Http\Controllers\RidePublicController;
use App\Http\Controllers\ReservaPasajeroController;
use App\Http\Controllers\MisReservasPasajeroController;

Route::post('/rides/publicos', [RidePublicController::class, 'listar']);

Route::post('/reservas/crear', [ReservaPasajeroController::class, 'reservar']);

Route::post('/reservas/mias', [MisReservasPasajeroController::class, 'listar']);
Route::post('/reservas/cancelar', [ReservaPasajeroController::class, 'cancelar']);


use App\Http\Controllers\ReservaChoferController;
Route::post('/chofer/reservas', [ReservaChoferController::class, 'listar']);
Route::post('/chofer/reserva/aceptar', [ReservaPasajeroController::class, 'aceptar']);
Route::post('/chofer/reserva/rechazar', [ReservaPasajeroController::class, 'rechazar']);




// Página principal
Route::get('/', function () {
    return view('home');
});

// API pública con filtro
Route::post('/rides/publicos', [RidePublicController::class, 'listar']);
