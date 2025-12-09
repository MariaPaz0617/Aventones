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

// Menús según rol
Route::get('/menu/chofer', function () { return view('menu_chofer'); })->name('menu.chofer');
Route::get('/menu/pasajero', function () { return view('menu_pasajero'); })->name('menu.pasajero');
Route::get('/menu/admin', function () { return view('menu_admin'); })->name('menu.admin');
Route::get('/menu/superadmin', function () { return view('menu_superadmin'); })->name('menu.superadmin');

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

