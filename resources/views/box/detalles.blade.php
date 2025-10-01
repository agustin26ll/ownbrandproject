<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Detalles caja de intereses | OwnBrand</title>

    @if(env('APP_ENV') === 'production')
    <link rel="stylesheet" href="https://ownbrandproject.onrender.com/build/assets/css/detalles.css">
    <script src="https://ownbrandproject.onrender.com/build/assets/js/detalles.js" defer></script>
    @else
    @vite(['resources/js/detalles.js'])
    @endif
</head>

<body>
    <header>
        <div class="logo">OwnBrand</div>
        <nav>
            <a href="/" id="homeLink">Inicio</a>
            <a href="/login" id="loginLink">Login</a>
            <a href="#" id="logoutLink" style="display:none">Cerrar sesión</a>
            <span id="userName" style="margin-left:15px"></span>
        </nav>
    </header>
    <main class="detalles-layout" data-caja-id="{{ $id }}">
        <!-- Productos -->
        <section id="cajaDetalles" class="caja-detalles">
            <h2 class="titulo-seccion">Tu Caja de Intereses</h2>
            <section id="envioInfo" class="envio-info"></section>
            <div id="productosGrid" class="productos-grid"></div>
        </section>

        <!-- Aside de pago -->
        <aside class="pago-aside">
            <h3 class="aside-title">Resumen de pago</h3>
            <div id="resumenPrecios" class="resumen-precios"></div>

            <form id="formPago" class="form-pago">
                <div class="input-wrapper">
                    <label for="metodo">Método de pago</label>
                    <select id="metodo" required>
                        <option value="">Seleccionar</option>
                        <option value="tarjeta">Tarjeta de crédito</option>
                    </select>
                </div>

                <!-- Inputs de tarjeta -->
                <div id="inputsTarjeta" class="tarjeta-form">
                    <div class="input-wrapper">
                        <label>Número de tarjeta</label>
                        <input type="text" maxlength="16" placeholder="•••• •••• •••• ••••" required>
                    </div>

                    <div class="input-wrapper">
                        <label>Nombre en la tarjeta</label>
                        <input type="text" placeholder="Nombre completo" required>
                    </div>

                    <div class="fila-mitad">
                        <div class="input-wrapper">
                            <label>Expiración</label>
                            <input type="month" required>
                        </div>
                        <div class="input-wrapper">
                            <label>CVC</label>
                            <input type="text" maxlength="4" placeholder="123" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-pago">Confirmar pago</button>
            </form>
        </aside>
    </main>
</body>

</html>