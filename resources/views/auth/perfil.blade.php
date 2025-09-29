<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard OwnBrand</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/58e42bf76d.js" crossorigin="anonymous"></script>
    @if(env('APP_ENV') === 'production')
    <link rel="stylesheet" href="https://ownbrandproject.onrender.com/build/assets/css/perfil.css">
    <script src="https://ownbrandproject.onrender.com/build/assets/js/perfil.js" defer></script>
    @else
    @vite(['resources/js/perfil.js'])
    @endif
</head>

<body>
    <header>
        <div class="logo">OwnBrand</div>
        <nav>
            <a href="/" id="homeLink">Inicio</a>
            <a href="/mis-cajas" id="misCajasLink">Mis Cajas</a>
            <a href="/login" id="loginLink">Login</a>
            <a href="#" id="logoutLink" style="display:none">Cerrar sesión</a>
            <span id="userName" style="margin-left:15px"></span>
        </nav>
    </header>

    <main>
        <h1>Bienvenido a tu panel</h1>
        <div id="contenidoUsuario"></div>
        <div id="cajasContainer"></div>
        <div id="enviosContainer"></div> <!-- <-- Agregado -->
    </main>
</body>

</html>