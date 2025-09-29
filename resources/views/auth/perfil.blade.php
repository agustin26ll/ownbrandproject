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
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const token = localStorage.getItem('token');
            const loginLink = document.getElementById('loginLink');
            const logoutLink = document.getElementById('logoutLink');
            const userName = document.getElementById('userName');
            const contenidoUsuario = document.getElementById('contenidoUsuario');
            const cajasContainer = document.getElementById('cajasContainer');

            // Función para cerrar sesión
            logoutLink.addEventListener('click', () => {
                localStorage.removeItem('token');
                window.location.href = '/login';
            });

            if (!token) {
                loginLink.style.display = 'inline';
                return;
            }

            loginLink.style.display = 'none';
            logoutLink.style.display = 'inline';

            try {
                // Obtener info del usuario
                const resUsuario = await fetch('/api/usuario', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!resUsuario.ok) throw new Error('Token inválido');

                const usuario = await resUsuario.json();
                userName.textContent = usuario.nombre;

                contenidoUsuario.innerHTML = `
                    <h2>Datos del usuario:</h2>
                    <p><strong>Nombre:</strong> ${usuario.nombre}</p>
                    <p><strong>Correo:</strong> ${usuario.correo}</p>
                    <p><strong>Edad:</strong> ${usuario.edad}</p>
                `;

                // Cargar contenido protegido: /mis-cajas
                const resCajas = await fetch('/api/mis-cajas', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!resCajas.ok) throw new Error('No se pudo cargar las cajas');

                const cajas = await resCajas.json();
                cajasContainer.innerHTML = '<h2>Mis cajas:</h2>';
                cajas.forEach(caja => {
                    const div = document.createElement('div');
                    div.classList.add('card');
                    div.innerHTML = `
                        <h3>${caja.tipo}</h3>
                        <p>Frecuencia: ${caja.frecuencia}</p>
                        <p>Contenido: ${caja.contenido.join(', ')}</p>
                    `;
                    cajasContainer.appendChild(div);
                });

            } catch (error) {
                console.error(error);
                localStorage.removeItem('token');
                Swal.fire({
                    icon: 'error',
                    title: 'Sesión inválida',
                    text: 'Por favor inicia sesión de nuevo',
                    confirmButtonColor: '#d33'
                }).then(() => {
                    window.location.href = '/login';
                });
            }
        });
    </script>
</body>

</html>