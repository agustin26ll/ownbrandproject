<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Bienvenido a OwnBrand</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="encabezado">
        <!-- NAV -->
        <nav>
            <div class="logo">
                <a href="#">OwnBrand</a>
            </div>
            <ul class="menu">
                <li><a href="#home">Inicio</a></li>
                <li><a href="#search">Busqueda</a></li>
                <li><a href="#">Tienda</a></li>
            </ul>
        </nav>

        <!-- HERO -->
        <section class="seccion_hero">
            <div class="hero_titulo">
                <h1 class="titulo">Tu estilo siempre en transformación</h1>
            </div>
            <div class="seccion_card">
                <div class="img-wrapper">
                    <img src="../img/header/IMG_001.png" class="img-principal" alt="">
                    <img src="../img/header/IMG_002.png" class="img-secundaria" alt="">
                </div>
            </div>
        </section>
    </header>
    <div class="transicion"></div>
    <main>
        <section class="seccion_personal" id="search">
            <div class="seccion_ropa">
                <h1>Selecciona la ropa de tu interés</h1>

                <input type="text" id="busqueda" class="search_bar" placeholder="Buscar productos...">

                <div class="grid" id="productosGrid"></div>
            </div>

            <div class="seccion_formulario">
                <h2>Ingresa tus datos para
                    conocer mas informacion</h2>
                <form id="formularioDatos">
                    <div class="fila-mitad">
                        <input type="text" id="nombre" placeholder="Nombre" required>
                        <input type="text" id="ocupacion" placeholder="Ocupación" required>
                    </div>

                    <div class="fila-mitad">
                        <input type="email" id="correo" placeholder="Correo electrónico" required>
                        <input type="number" id="edad" placeholder="Edad" min="18" max="100" style="width: 30%;" required>
                    </div>

                    <button type="submit">Enviar</button>
                </form>
            </div>
        </section>
    </main>
    <footer>

    </footer>
</body>

</html>