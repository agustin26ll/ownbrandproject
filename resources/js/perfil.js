import '../css/perfil.css';

const token = localStorage.getItem('token');
if (!token) {
    window.location.href = '/login';
}

document.addEventListener('DOMContentLoaded', async () => {
    const loginLink = document.getElementById('loginLink');
    const logoutLink = document.getElementById('logoutLink');
    const userName = document.getElementById('userName');

    const profileName = document.getElementById('profileName');
    const profileEmail = document.getElementById('profileEmail');
    const profileAvatar = document.querySelector('.profile-avatar');

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
        showLoader(cajasContainer);
        showLoader(enviosContainer);

        const usuario = await fetchConToken('/api/usuario');
        userName.textContent = usuario.nombre;
        profileName.textContent = usuario.nombre;
        profileEmail.textContent = usuario.correo;

        if (profileAvatar) {
            const nombreCodificado = encodeURIComponent(usuario.nombre);
            profileAvatar.src = `https://ui-avatars.com/api/?name=${nombreCodificado}&background=random&color=fff&size=128&bold=true`;
            profileAvatar.alt = `Avatar de ${usuario.nombre}`;
        }

        cajasContainer.innerHTML = '<h2>Mis Cajas</h2>';
        if (!usuario.cajas || usuario.cajas.length === 0) {
            cajasContainer.innerHTML += '<p>No tienes cajas registradas.</p>';
        } else {
            usuario.cajas.forEach(caja => {
                const div = document.createElement('div');
                div.classList.add('caja-card');

                const productos = caja.productos.map(p => p.nombre).join(', ');

                div.innerHTML = `
                    <h3>${caja.nombre}</h3>
                    <p>Tipo de caja: ${caja.id_tipo_caja}</p>
                    <p>Contenido: ${productos}</p>
                    <div class="progreso">
                        <div class="barra" style="width: ${Math.min(100, caja.progreso || 0)}%"></div>
                    </div>
                    <button class="btn-ver">Ver detalles</button>
                `;
                cajasContainer.appendChild(div);
            });
        }

        enviosContainer.innerHTML = '<h2>Historial de Envíos</h2>';
        if (!usuario.envios || usuario.envios.length === 0) {
            enviosContainer.innerHTML += '<p>No tienes envíos registrados.</p>';
        } else {
            usuario.envios.forEach(envio => {
                const div = document.createElement('div');
                div.classList.add('card');

                div.innerHTML = `
                    <h3>Envío #${envio.id}</h3>
                    <p><strong>Estado:</strong> ${envio.estado}</p>
                    <p><strong>Fecha:</strong> ${envio.fecha}</p>
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
