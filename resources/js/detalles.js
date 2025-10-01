import '../css/detalles.css';
import { EstadoEnvio, FrecuenciaCaja } from '../js/enums.js';

const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

document.addEventListener("DOMContentLoaded", async () => {
    const loginLink = document.getElementById("loginLink");
    const logoutLink = document.getElementById("logoutLink");
    const userName = document.getElementById("userName");

    logoutLink.addEventListener("click", () => {
        localStorage.removeItem("token");
        window.location.href = "/login";
    });

    loginLink.style.display = token ? "none" : "inline";
    logoutLink.style.display = token ? "inline" : "none";

    let usuario = null;
    if (token) {
        try {
            const res = await fetch("/api/usuario", {
                headers: {
                    "X-Api-Token": token,
                    "Accept": "application/json"
                }
            });
            if (!res.ok) throw new Error("Token inválido");
            usuario = await res.json();
            userName.textContent = usuario.nombre || "";
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
    }

    const grid = document.getElementById("productosGrid");
    const resumenPrecios = document.getElementById("resumenPrecios");
    const envioInfo = document.getElementById("envioInfo");
    const metodo = document.getElementById("metodo");
    const inputsTarjeta = document.getElementById("inputsTarjeta");
    const formPago = document.getElementById("formPago");

    const cajaId = document.querySelector("main").dataset.cajaId;

    try {
        const resCaja = await fetch(`/api/caja/${cajaId}`, {
            headers: {
                "X-Api-Token": token,
                "Accept": "application/json"
            }
        });

        if (!resCaja.ok) throw new Error("No se pudo obtener la caja");

        const caja = await resCaja.json();

        let total = 0;
        grid.innerHTML = "";

        if (caja.productos && caja.productos.length > 0) {
            for (const prod of caja.productos) {
                const precio = Number(prod.precio) || 0;
                total += precio;

                // 🔹 Traer imagen desde FakeStore API
                let imagenURL = '';
                try {
                    const resProd = await fetch(`https://fakestoreapi.com/products/${prod.api_id}`);
                    if (resProd.ok) {
                        const dataProd = await resProd.json();
                        imagenURL = dataProd.image || '';
                    }
                } catch (e) {
                    console.warn(`No se pudo obtener imagen para API_ID ${prod.api_id}`);
                }

                const card = document.createElement("div");
                card.className = "producto-card";
                card.innerHTML = `
                    <img src="${imagenURL}" alt="${prod.nombre || 'Producto'}">
                    <h4>${prod.nombre || 'Producto'}</h4>
                    <p>${prod.categoria || ''}</p>
                    <p><strong>$${precio.toFixed(2)}</strong></p>
                `;
                grid.appendChild(card);
            }
        } else {
            grid.innerHTML = `<p>No hay productos en esta caja.</p>`;
        }

        const descuento = total * 0.3;
        const totalFinal = total - descuento;

        resumenPrecios.innerHTML = `
            <p>Subtotal: $${total.toFixed(2)}</p>
            <p>Descuento (30%): -$${descuento.toFixed(2)}</p>
            <hr>
            <p><strong>Total: $${totalFinal.toFixed(2)}</strong></p>
        `;

        if (caja.envio) {
            const estado = EstadoEnvio[caja.envio.id_estado] || "Preparado";
            envioInfo.innerHTML = `
                <h3>Información del envío</h3>
                <p>Envío Nº ${caja.envio.id}</p>
                <p>Fecha: ${caja.envio.fecha_creacion ?? "—"}</p>
                <p>Estado Envío: 
                    <span class="estado ${estado.toLowerCase()}">${estado}</span>
                </p>
                <p>Dirección: ${caja.envio.direccion ?? "—"}</p>
            `;
        } else {
            envioInfo.innerHTML = `<p>No hay información de envío disponible.</p>`;
        }

    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo cargar la información de la caja",
            confirmButtonColor: "#d33"
        });
    }

    metodo.addEventListener("change", e => {
        inputsTarjeta.style.display = e.target.value === "tarjeta" ? "block" : "none";
    });

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
