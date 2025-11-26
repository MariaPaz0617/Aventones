<?php

use Illuminate\Support\Facades\Route;

// Vistas
Route::get('/login', function () { return view('login'); })->name('login.view');
Route::get('/registrar', function () { return view('crear_cuenta'); })->name('register.view');

// Menús según rol
Route::get('/menu/chofer', function () { return view('menu_chofer'); })->name('menu.chofer');
Route::get('/menu/pasajero', function () { return view('menu_pasajero'); })->name('menu.pasajero');
Route::get('/menu/admin', function () { return view('menu_admin'); })->name('menu.admin');

// API Login
Route::post('/api/login', [LoginController::class, 'login'])->name('login.api');

