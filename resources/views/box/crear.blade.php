<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/58e42bf76d.js" crossorigin="anonymous"></script>
    <title>Crear caja de intereses | OwnBrand</title>

    @if(env('APP_ENV') === 'production')
    <link rel="stylesheet" href="https://ownbrandproject.onrender.com/build/assets/css/cajas.css">
    <script src="https://ownbrandproject.onrender.com/build/assets/js/cajas.js" defer></script>
    @else
    @vite(['resources/js/cajas.js'])
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
    <section class="seccion_personal" id="tienda">
        <div class="seccion_ropa">
            <h1>Selecciona la ropa de tu interés</h1>

            <input type="text" id="busqueda" class="search_bar" placeholder="Buscar productos...">

            <div class="grid" id="productosGrid"></div>
        </div>

        <div class="seccion_formulario">
            <h2>Ingresa tus datos</h2>
            <form id="formularioDatos">
                <!-- Tipo de Caja y Frecuencia -->
                <div class="fila-mitad">
                    <div class="input-wrapper">
                        <label for="tipoCaja">Tipo de Caja</label>
                        <select id="tipoCaja" required>
                            <option value="" disabled selected>Selecciona un tipo</option>
                            <option value="1">Standard</option>
                            <option value="2">Premium</option>
                        </select>
                    </div>

                    <div class="input-wrapper">
                        <label for="frecuenciaCaja">Frecuencia de envío</label>
                        <select id="frecuenciaCaja" required>
                            <option value="" disabled selected>Selecciona la frecuencia</option>
                            <option value="1">Mensual</option>
                            <option value="2">Bimensual</option>
                            <option value="3">Por temporada</option>
                        </select>
                    </div>
                </div>

                <!-- Datos personales -->
                <div class="fila-mitad">
                    <div class="input-wrapper">
                        <input type="text" id="nombre" placeholder="Nombre" required>
                        <span class="error-message" id="error-nombre"></span>
                    </div>
                    <div class="input-wrapper">
                        <input type="text" id="ocupacion" placeholder="Ocupación" required>
                        <span class="error-message" id="error-ocupacion"></span>
                    </div>
                </div>

                <div class="fila-mitad">
                    <div class="input-wrapper">
                        <input type="email" id="correo" placeholder="Correo electrónico" required>
                        <span class="error-message" id="error-correo"></span>
                    </div>
                    <div class="input-wrapper">
                        <input type="number" id="edad" placeholder="Edad" min="18" max="100" required>
                        <span class="error-message" id="error-edad"></span>
                    </div>
                </div>

                <div class="fila-mitad">
                    <div class="input-wrapper">
                        <input type="text" id="direccion" placeholder="Dirección" required>
                    </div>
                </div>

                <button type="submit">Enviar</button>
            </form>
        </div>
    </section>

</body>

</html>