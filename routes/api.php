<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::post('registro', [UsuarioController::class, 'registro']);
Route::post('login', [UsuarioController::class, 'iniciarSesion']);
Route::post('contacto', [UsuarioController::class, 'enviarCorreo']);

Route::middleware('jwt')->group(function () {
    Route::get('usuario', [UsuarioController::class, 'consultarToken']);
});
