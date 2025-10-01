import '../css/perfil.css';

export const EstadoEnvio = {
    1: "Preparado",
    2: "Enviado",
    3: "Recibido"
};

export const FrecuenciaCaja = {
    1: "Mensual",
    2: "Bimensual",
    3: "Temporada"
};

const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

document.addEventListener('DOMContentLoaded', async () => {
    const loginLink = document.getElementById('loginLink');
    const logoutLink = document.getElementById('logoutLink');
    const userName = document.getElementById('userName');
    const profileName = document.getElementById('profileName');
    const profileEmail = document.getElementById('profileEmail');
    const profileAvatar = document.querySelector('.profile-avatar');
    const cajasContainer = document.getElementById('cajasContainer');

    const showLoader = (container) => {
        if (!container) return;
        container.innerHTML = `<div class="loader">Cargando...</div>`;
    };

    logoutLink.addEventListener('click', () => {
        localStorage.removeItem('token');
        window.location.href = '/login';
    });

    loginLink.style.display = token ? 'none' : 'inline';
    logoutLink.style.display = token ? 'inline' : 'none';

    const fetchConToken = async (url) => {
        const res = await fetch(url, {
            headers: { 'X-Api-Token': token, 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('Error en la petición o token inválido');
        return res.json();
    };

    const sanitize = (str) => (str == null ? '' : String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;'));

    try {
        showLoader(cajasContainer);
        const usuario = await fetchConToken('/api/usuario');

        userName.textContent = usuario.nombre || '';
        profileName.textContent = usuario.nombre || '';
        profileEmail.textContent = usuario.correo || '';

        if (profileAvatar) {
            const nombreCodificado = encodeURIComponent(usuario.nombre || 'Usuario');
            profileAvatar.src = `https://ui-avatars.com/api/?name=${nombreCodificado}&background=random&color=fff&size=128&bold=true`;
            profileAvatar.alt = `Avatar de ${usuario.nombre || 'Usuario'}`;
        }

        cajasContainer.innerHTML = '';

        if (!usuario.cajas || usuario.cajas.length === 0) {
            cajasContainer.innerHTML = '<p>No tienes cajas registradas.</p>';
            return;
        }

        usuario.cajas.forEach(caja => {
            const div = document.createElement('div');
            div.classList.add('caja-card');

            const productos = Array.isArray(caja.productos)
                ? caja.productos.map(p => sanitize(p.nombre)).join(', ')
                : '—';

            const envioRelacionado = usuario.envios.find(e => e.id === caja.id_envio);
            const estadoCajaLabel = envioRelacionado ? EstadoEnvio[envioRelacionado.id_estado] : 'Preparado';
            const frecuenciaLabel = FrecuenciaCaja[caja.id_tipo_caja] || '—';

            let inner = `
                <h3>${sanitize(caja.nombre || 'Caja')}</h3>
                <p>Frecuencia: ${sanitize(frecuenciaLabel)}</p>
                <p>Contenido: ${productos}</p>
            `;

            if (envioRelacionado) {
                inner += `
                    <div class="envio-info">
                        <p>Envío Nº ${sanitize(envioRelacionado.id)}</p>
                        <p>Fecha:${sanitize(caja.fecha_creacion ?? '—')}</p>
                        <p>Estado Envío: <span class="estado ${EstadoEnvio[envioRelacionado.id_estado].toLowerCase()}">${sanitize(EstadoEnvio[envioRelacionado.id_estado])}</span></p>
                    </div>
                `;
            }

            inner += `<a class="btn-ver" href="/detalles-caja/${sanitize(caja.id)}">Ver detalles</a>`;
            div.innerHTML = inner;
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
        }).then(() => window.location.href = '/login');
    }
});
