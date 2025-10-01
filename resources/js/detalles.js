import '../css/detalles.css';

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

document.addEventListener("DOMContentLoaded", async () => {
    // 🔹 Referencias del nav
    const loginLink = document.getElementById("loginLink");
    const logoutLink = document.getElementById("logoutLink");
    const userName = document.getElementById("userName");

    // 🔹 Referencias de la UI
    const grid = document.getElementById("productosGrid");
    const resumenPrecios = document.getElementById("resumenPrecios");
    const envioInfo = document.getElementById("envioInfo");
    const metodo = document.getElementById("metodo");
    const inputsTarjeta = document.getElementById("inputsTarjeta");
    const formPago = document.getElementById("formPago");

    // 🔹 Configurar logout
    logoutLink.addEventListener("click", () => {
        localStorage.removeItem("token");
        window.location.href = "/login";
    });

    loginLink.style.display = token ? "none" : "inline";
    logoutLink.style.display = token ? "inline" : "none";

    // 🔹 Obtener usuario autenticado
    let usuario = null;
    try {
        const res = await fetch("/api/usuario", {
            headers: { "X-Api-Token": token, "Accept": "application/json" }
        });
        if (!res.ok) throw new Error("Token inválido");
        usuario = await res.json();
        userName.textContent = usuario.nombre || "Usuario";
    } catch (error) {
        console.error(error);
        localStorage.removeItem("token");
        Swal.fire({
            icon: "error",
            title: "Sesión inválida",
            text: "Por favor inicia sesión de nuevo",
            confirmButtonColor: "#d33"
        }).then(() => window.location.href = "/login");
        return;
    }

    // 🔹 Obtener ID de la caja
    const cajaId = document.querySelector("main").dataset.cajaId;

    try {
        // 🔹 Traer caja del backend
        const resCaja = await fetch(`/api/caja/${cajaId}`, {
            headers: { "X-Api-Token": token, "Accept": "application/json" }
        });
        if (!resCaja.ok) throw new Error("No se pudo obtener la caja");

        const caja = await resCaja.json();
        const productos = caja.productos || [];
        const envio = caja.envio || {};
        const usuarioCaja = caja.usuario || {};
        const frecuenciaLabel = FrecuenciaCaja[caja.id_tipo_caja] || '—';
        const estadoLabel = EstadoEnvio[envio.id_estado] || 'Preparado';

        // 🔹 Pintar productos con fetch concurrente de imágenes
        grid.innerHTML = '';
        let total = 0;

        const productosCards = await Promise.all(productos.map(async (prod) => {
            const precio = Number(prod.precio) || 0;
            total += precio;

            let imagenURL = '';
            try {
                const resProd = await fetch(`https://fakestoreapi.com/products/${prod.api_id}`);
                if (resProd.ok) {
                    const dataProd = await resProd.json();
                    imagenURL = dataProd.image || '';
                }
            } catch {
                console.warn(`No se pudo obtener imagen para API_ID ${prod.api_id}`);
            }

            const card = document.createElement('div');
            card.className = 'producto-card';
            card.innerHTML = `
                <img src="${imagenURL}" alt="${prod.nombre || 'Producto'}" class="producto-img">
                <h4>${prod.nombre || 'Producto'}</h4>
                <p>${prod.categoria || ''}</p>
                <p><strong>$${precio.toFixed(2)}</strong></p>
            `;
            return card;
        }));

        productosCards.forEach(card => grid.appendChild(card));

        if (productos.length === 0) {
            grid.innerHTML = `<p>No hay productos en esta caja.</p>`;
        }

        // 🔹 Resumen de precios
        const descuento = total * 0.3;
        const totalFinal = total - descuento;

        resumenPrecios.innerHTML = `
            <p>Frecuencia: ${frecuenciaLabel}</p>
            <p>Subtotal: $${total.toFixed(2)}</p>
            <p>Descuento (30%): -$${descuento.toFixed(2)}</p>
            <hr>
            <p><strong>Total: $${totalFinal.toFixed(2)}</strong></p>
        `;

        // 🔹 Información de envío
        envioInfo.innerHTML = envio.id ? `
            <h3>Información del envío</h3>
            <p>Envío Nº ${envio.id}</p>
            <p>Correo: ${envio.correo || '—'}</p>
            <p>Dirección: ${envio.direccion || '—'}</p>
            <p>Estado: <span class="estado ${estadoLabel.toLowerCase()}">${estadoLabel}</span></p>
            <p>Fecha: ${caja.fecha_creacion ?? '—'}</p>
        ` : `<p>No hay información de envío disponible.</p>`;

    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo cargar la información de la caja",
            confirmButtonColor: "#d33"
        });
    }

    // 🔹 Mostrar inputs según método
    metodo.addEventListener("change", e => {
        inputsTarjeta.style.display = e.target.value === "tarjeta" ? "block" : "none";
    });

    // 🔹 Simulación de pago
    formPago.addEventListener("submit", e => {
        e.preventDefault();
        Swal.fire({
            icon: "info",
            title: "Simulación",
            text: "Esta funcionalidad es solo una simulación. No es posible realizar el pago.",
            confirmButtonColor: "#007bff"
        });
    });
});
