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

    <main class="dashboard">
        <div class="dashboard-main">
            <h1>Bienvenido a tu panel</h1>

            <section id="cajasEnviosContainer">
                <div class="section-header">
                    <h2>Mis cajas</h2>
                    <button class="btn-primary">Crear Caja</button>
                </div>
                <div class="cards-container" id="cajasContainer">

                </div>
            </section>
        </div>

        <aside class="dashboard-profile card">
            <div class="profile-header">
                <img src="" alt="Avatar" class="profile-avatar">
                <h2 id="profileName"></h2>
                <p id="profileEmail"></p>
            </div>
            <div class="profile-actions">
                <button class="btn-primary">Editar Perfil</button>
            </div>
        </aside>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h2 class="footer-logo">OwnBrand</h2>
                <p>Transforma tu estilo cada día con prendas seleccionadas especialmente para ti.</p>
                <div class="footer-redes">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-x"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Enlaces útiles</h3>
                <ul>
                    <li><a href="#home">Inicio</a></li>
                    <li><a href="#search">Buscar Productos</a></li>
                    <li><a href="#">Tienda</a></li>
                    <li><a href="#">Perfil</a></li>
                </ul>
            </div>

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