<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/58e42bf76d.js" crossorigin="anonymous"></script>
    <title>Crea una nueva cuenta en OwnBrand</title>
    @if(env('APP_ENV') === 'production')
    <link rel="stylesheet" href="https://ownbrandproject.onrender.com/build/assets/css/registro.css">
    <script src="https://ownbrandproject.onrender.com/build/assets/js/registro.js" defer></script>
    @else
    @vite(['resources/js/registro.js'])
    @endif
</head>

<body>
</body>
<section class="background_reg">
    <img src="../../img/bgFormRegistro.webp" class="img-principal" alt="">
</section>

<main class="contenedor_formulario">
    <form id="formularioDatos">
        <h2>Ingresa tus datos para comenzar</h2>
        <input type="name" id="nombre" placeholder="Nombre completo" required>
        <input type="email" id="correo" placeholder="Correo electrónico" required>
        <div class="grupo_input fila_dos_inputs">
            <div class="input_wrapper">
                <input type="password" id="contrasenia" placeholder="Contraseña" required>
                <i class="fa-solid fa-eye" id="togglePassword"></i>
            </div>
            <div class="input_wrapper">
                <input type="number" id="edad" placeholder="Edad" min="18" max="100" required>
            </div>
        </div>

        <button type="submit">Continuar <i class="fa-solid fa-arrow-right"></i></button>

        <div class="or-divider">Siguenos en nuestras redes sociales</div>

        <div class="social-login">
            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-x"></i></a>
        </div>

        <p style="text-align:center; margin-top:15px; font-size:0.9rem;">
            ¿Ya tienes una cuenta? <a href="/login" style="color:#4eb5d4;">Ingresa aquí</a>
        </p>
    </form>
</main>
<div class="boton_flotante">
    <a href="/" class="btn-regresar" aria-label="Regresar">
        <i class="fa-solid fa-arrow-left"></i>
        <span class="tooltip">Regresar</span>
    </a>
</div>

</html>