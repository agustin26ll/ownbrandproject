<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia sesion en OwnBrand</title>
    <script src="https://kit.fontawesome.com/58e42bf76d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(env('APP_ENV') === 'production')
    <link rel="stylesheet" href="https://ownbrandproject.onrender.com/build/assets/css/login.css">
    <script src="https://ownbrandproject.onrender.com/build/assets/js/login.js" defer></script>
    @else
    @vite(['resources/js/login.js'])
    @endif
</head>

<body>
    <section class="background_reg">
        <img src="../../img/bgFormLogin.webp" class="img-principal" alt="">
    </section>

    <main class="contenedor_formulario">
        <form id="formularioDatos">
            <h2>Bienvenido de nuevo</h2>
            <input type="email" id="correo" placeholder="Correo electrónico" required>
            <div class="grupo_input">
                <input type="password" id="contrasenia" placeholder="Contraseña" required>
                <i class="fa-solid fa-eye" id="togglePassword"></i>
            </div>

            <button type="submit">Continuar <i class="fa-solid fa-arrow-right"></i></button>

            <div class="or-divider">Siguenos en nuestras redes sociales</div>

            <div class="social-login">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-x"></i></a>
            </div>

            <p style="text-align:center; margin-top:15px; font-size:0.9rem;">
                ¿No tienes una cuenta? <a href="/registrarse" style="color:#4eb5d4;">Create una</a>
            </p>
        </form>
    </main>
    <div class="boton_flotante">
        <a href="/" class="btn-regresar" aria-label="Regresar">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="tooltip">Regresar</span>
        </a>
    </div>
</body>

</html>