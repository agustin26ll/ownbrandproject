import '../css/perfil.css';

document.addEventListener('DOMContentLoaded', async () => {
    const loginLink = document.getElementById('loginLink');
    const logoutLink = document.getElementById('logoutLink');
    const userName = document.getElementById('userName');
    const contenidoUsuario = document.getElementById('contenidoUsuario');
    const cajasContainer = document.getElementById('cajasContainer');
    const enviosContainer = document.getElementById('enviosContainer');

    const showLoader = (container) => {
        container.innerHTML = `<div class="loader">Cargando...</div>`;
    };

    logoutLink.addEventListener('click', () => {
        localStorage.removeItem('token');
        window.location.href = '/login';
    });

    const token = localStorage.getItem('token');

    if (!token) {
        loginLink.style.display = 'inline';
        logoutLink.style.display = 'none';
        return;
    }

    loginLink.style.display = 'none';
    logoutLink.style.display = 'inline';

    const fetchConToken = async (url) => {
        const res = await fetch(url, {
            headers: {
                'X-Api-Token': token,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('Error en la petición o token inválido');
        return res.json();
    };

    try {
        // Mostrar loader mientras cargan datos
        showLoader(contenidoUsuario);
        showLoader(cajasContainer);
        showLoader(enviosContainer);

        const usuario = await fetchConToken('/api/usuario');

        // Datos generales del usuario
        userName.textContent = usuario.nombre;
        contenidoUsuario.innerHTML = `
            <h2>Datos del usuario:</h2>
            <p><strong>Nombre:</strong> ${usuario.nombre}</p>
            <p><strong>Correo:</strong> ${usuario.correo}</p>
            <p><strong>Edad:</strong> ${usuario.edad}</p>
        `;

        // Mostrar cajas con productos
        cajasContainer.innerHTML = '<h2>Mis cajas:</h2>';
        usuario.cajas.forEach(caja => {
            const div = document.createElement('div');
            div.classList.add('card');

            const productos = caja.productos.map(p => p.nombre).join(', ');

            div.innerHTML = `
                <h3>${caja.nombre}</h3>
                <p>Tipo de caja: ${caja.id_tipo_caja}</p>
                <p>Contenido: ${productos}</p>
            `;

            cajasContainer.appendChild(div);
        });

        // Mostrar envíos del usuario
        enviosContainer.innerHTML = '<h2>Mis envíos:</h2>';
        if (usuario.envios.length === 0) {
            enviosContainer.innerHTML += '<p>No tienes envíos registrados.</p>';
        } else {
            usuario.envios.forEach(envio => {
                const div = document.createElement('div');
                div.classList.add('card');

                div.innerHTML = `
                    <p><strong>Nombre:</strong> ${envio.nombre}</p>
                    <p><strong>Correo:</strong> ${envio.correo}</p>
                    <p><strong>Edad:</strong> ${envio.edad}</p>
                `;

                enviosContainer.appendChild(div);
            });
        }

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
