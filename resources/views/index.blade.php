<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/58e42bf76d.js" crossorigin="anonymous"></script>
    <title>Bienvenido a OwnBrand</title>

    @if(env('APP_ENV') === 'production')
    <link rel="stylesheet" href="https://ownbrandproject.onrender.com/build/assets/css/main.css">
    <script src="https://ownbrandproject.onrender.com/build/assets/js/main.js" defer></script>
    @else
    @vite(['resources/js/main.js'])
    @endif
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
                <li><a href="/login" id="perfilLink">Perfil</a></li>
                <li><a href="#" id="logout" style="display:none">Cerrar sesión</a></li>
            </ul>
        </nav>

        <!-- HERO -->
        <section class="seccion_hero">
            <div class="hero_titulo">
                <h1 class="titulo">Tu estilo siempre en transformación</h1>
            </div>
            <div class="seccion_card">
                <div class="img-wrapper">
                    <img src="../img/header/IMG_001.webp" class="img-principal" alt="">
                    <img src="../img/header/IMG_002.webp" class="img-secundaria" alt="">
                </div>
            </div>
        </section>
    </header>
    <div class="transicion"></div>
    <main>
        <section class="seccion_planes" id="planes">
            <div class="planes-container">
                <div class="planes-info">
                    <h2 class="titulo">Conoce nuestros planes</h2>
                    <p class="texto">A continuación te mostramos los tipos de cajas que ofrecemos y la frecuencia de envío disponible para que elijas la que más te convenga.</p>

                    <div class="cards-tipos">
                        <div class="card-tipo" id="card-standard">
                            <h3>Standard</h3>
                            <p>Incluye:</p>
                            <ul>
                                <li>1 Camisa</li>
                                <li>1 Pantalón</li>
                                <li>2 Accesorios</li>
                            </ul>
                            <p>Ideal si quieres recibir lo esencial para tu estilo.</p>
                        </div>

                        <div class="card-tipo" id="card-premium">
                            <h3>Premium</h3>
                            <p>Incluye:</p>
                            <ul>
                                <li>1 Camisa</li>
                                <li>1 Pantalón</li>
                                <li>3 Accesorios</li>
                                <li>1 Par de Zapatos</li>
                            </ul>
                            <p>Perfecta si quieres un look completo y accesorios adicionales.</p>
                        </div>
                    </div>

                    <div class="cards-frecuencia">
                        <div class="card-frec" id="frec-mensual">
                            <h4>Mensual</h4>
                            <p>Recibe una caja cada mes.</p>
                        </div>
                        <div class="card-frec" id="frec-bimensual">
                            <h4>Bimensual</h4>
                            <p>Recibe una caja cada dos meses.</p>
                        </div>
                        <div class="card-frec" id="frec-temporada">
                            <h4>Por temporada</h4>
                            <p>Recibe una caja por temporada (aprox. cada 3 meses).</p>
                        </div>
                    </div>
                </div>

                <div class="planes-imagen">
                    <img src="https://images.pexels.com/photos/325876/pexels-photo-325876.jpeg" alt="Caja OwnBrand" class="imagen-animada">
                </div>
            </div>
        </section>


        <section class="seccion_personal" id="search">
            <div class="seccion_ropa">
                <h1>Selecciona la ropa de tu interés</h1>

                <input type="text" id="busqueda" class="search_bar" placeholder="Buscar productos...">

                <div class="grid" id="productosGrid"></div>
            </div>

            <div class="seccion_formulario">
                <h2>Ingresa tus datos para conocer más información</h2>
                <form id="formularioDatos">

                    <!-- Nuevos inputs: Tipo de Caja y Frecuencia -->
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

                    <button type="submit">Enviar</button>
                </form>
            </div>
        </section>

    </main>
    <footer class="footer">
        <div class="footer-container">
            <!-- Marca y Redes -->
            <div class="footer-section">
                <h2 class="footer-logo">OwnBrand</h2>
                <p>Transforma tu estilo cada día con prendas seleccionadas especialmente para ti.</p>
                <div class="footer-redes">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-x"></i></a>
                </div>
            </div>

            <!-- Enlaces útiles -->
            <div class="footer-section">
                <h3>Enlaces útiles</h3>
                <ul>
                    <li><a href="#home">Inicio</a></li>
                    <li><a href="#search">Buscar Productos</a></li>
                    <li><a href="#">Tienda</a></li>
                    <li><a href="#">Perfil</a></li>
                </ul>
            </div>

            <!-- Contacto -->
            <div class="footer-section">
                <h3>Contacto</h3>
                <p>Email: <a href="mailto:hello@ownbrand.com">hello@ownbrand.com</a></p>
                <p>Tel: +57 300 000 0000</p>
                <p>Dirección: Medellín, Colombia</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 OwnBrand. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>

</html>