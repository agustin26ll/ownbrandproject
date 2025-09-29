<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/registrarse', function () {
    return view('auth.registro');
});

Route::get('/perfil', function () {
    return view('auth.perfil');
});
