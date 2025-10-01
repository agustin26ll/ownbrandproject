import '../css/cajas.css';

// ------------------- Nav y usuario -------------------
const token = localStorage.getItem('token');
const loginLink = document.getElementById('loginLink');
const logoutLink = document.getElementById('logoutLink');
const userName = document.getElementById('userName');

loginLink.style.display = token ? 'none' : 'inline';
logoutLink.style.display = token ? 'inline' : 'none';

logoutLink.addEventListener('click', () => {
    localStorage.removeItem('token');
    window.location.href = '/login';
});

const fetchConToken = async (url) => {
    const res = await fetch(url, {
        headers: { 'X-Api-Token': token, 'Accept': 'application/json' }
    });
    if (!res.ok) throw new Error('Error en la petición o token inválido');
    return res.json();
};

// ------------------- Productos -------------------
const gridProductos = document.getElementById("productosGrid");
const inputBusqueda = document.getElementById("busqueda");
let productos = [];
let seleccionados = [];

async function obtenerProductos() {
    const res = await fetch("https://fakestoreapi.com/products");
    productos = await res.json();
    mostrarProductos(productos.slice(0, 6));
}

function mostrarProductos(lista) {
    gridProductos.innerHTML = "";
    lista.forEach(producto => {
        const tarjeta = document.createElement("div");
        tarjeta.classList.add("product-card");
        tarjeta.innerHTML = `
            <img src="${producto.image}" alt="${producto.title}">
            <h4>${producto.title}</h4>
        `;
        tarjeta.addEventListener("click", () => {
            const existe = seleccionados.find(p => p.id === producto.id);
            if (existe) {
                seleccionados = seleccionados.filter(p => p.id !== producto.id);
                tarjeta.classList.remove("selected");
            } else {
                if (seleccionados.length < 4) {
                    seleccionados.push({ ...producto });
                    tarjeta.classList.add("selected");
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Máximo 4 productos seleccionados',
                        confirmButtonColor: '#f0ad4e'
                    });
                }
            }
        });
        gridProductos.appendChild(tarjeta);
    });
}

inputBusqueda.addEventListener("keyup", e => {
    const texto = e.target.value.toLowerCase();
    const filtrados = productos.filter(p => p.title.toLowerCase().includes(texto));
    mostrarProductos(filtrados.slice(0, 6));
});

// ------------------- Formulario -------------------
const formulario = document.getElementById("formularioDatos");
const nombre = document.getElementById("nombre");
const ocupacion = document.getElementById("ocupacion");
const correo = document.getElementById("correo");
const edad = document.getElementById("edad");
const tipoCaja = document.getElementById("tipoCaja");
const frecuenciaCaja = document.getElementById("frecuenciaCaja");
const direccion = document.getElementById("direccion");

const expresiones = {
    texto: /^[a-zA-ZÀ-ÿ\s]{6,40}$/,
    correo: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
};

function validarInput(input, tipo) {
    const wrapper = input.parentElement;
    const errorSpan = wrapper.querySelector(".error-message");
    let valido = true;

    if (tipo === "texto") {
        if (!expresiones.texto.test(input.value.trim())) {
            errorSpan.textContent = "Solo letras y espacios, mínimo 6 caracteres.";
            wrapper.classList.add("error");
            valido = false;
        } else {
            errorSpan.textContent = "";
            wrapper.classList.remove("error");
        }
    }

    if (tipo === "correo") {
        if (!expresiones.correo.test(input.value.trim())) {
            errorSpan.textContent = "Correo inválido (ej: usuario@dominio.com).";
            wrapper.classList.add("error");
            valido = false;
        } else {
            errorSpan.textContent = "";
            wrapper.classList.remove("error");
        }
    }

    if (tipo === "edad") {
        const valor = parseInt(input.value, 10);
        if (isNaN(valor) || valor < 18 || valor > 100) {
            errorSpan.textContent = "Ingresa un número entre 18 y 100.";
            wrapper.classList.add("error");
            valido = false;
        } else {
            errorSpan.textContent = "";
            wrapper.classList.remove("error");
        }
    }

    return valido;
}

// Validación en tiempo real
[nombre, ocupacion, correo, edad].forEach(input => {
    input.addEventListener("input", () => {
        if (input === nombre || input === ocupacion) validarInput(input, "texto");
        if (input === correo) validarInput(input, "correo");
        if (input === edad) validarInput(input, "edad");
    });
});

// Enviar formulario
formulario.addEventListener("submit", async e => {
    e.preventDefault();

    const nombreValido = validarInput(nombre, "texto");
    const ocupacionValido = validarInput(ocupacion, "texto");
    const correoValido = validarInput(correo, "correo");
    const edadValida = validarInput(edad, "edad");

    if (!nombreValido || !ocupacionValido || !correoValido || !edadValida) {
        Swal.fire({ icon: 'warning', title: 'Corrige los errores', confirmButtonColor: '#f0ad4e' });
        return;
    }

    if (seleccionados.length === 0) {
        Swal.fire({ icon: 'warning', title: 'Selecciona al menos un producto', confirmButtonColor: '#f0ad4e' });
        return;
    }

    if (!tipoCaja.value || !frecuenciaCaja.value) {
        Swal.fire({ icon: 'warning', title: 'Selecciona tipo de caja y frecuencia', confirmButtonColor: '#f0ad4e' });
        return;
    }

    const datos = {
        nombre: nombre.value,
        ocupacion: ocupacion.value,
        correo: correo.value,
        edad: edad.value,
        direccion: direccion.value,
        tipoCaja: tipoCaja.value,
        frecuenciaCaja: frecuenciaCaja.value,
        productos: seleccionados
    };

    try {
        const res = await fetch('/api/contacto', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(datos)
        });
        const data = await res.json();

        if (!res.ok) {
            Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje || 'Error al enviar datos', confirmButtonColor: '#d33' });
            return;
        }

        Swal.fire({ icon: 'success', title: '¡Perfecto!', text: data.mensaje || 'Datos enviados correctamente', confirmButtonColor: '#007bff' });
        formulario.reset();
        seleccionados = [];
        document.querySelectorAll(".product-card.selected").forEach(c => c.classList.remove("selected"));

    } catch (error) {
        console.error(error);
        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Error al enviar los datos', confirmButtonColor: '#d33' });
    }
});

// ------------------- Inicialización -------------------
document.addEventListener("DOMContentLoaded", async () => {
    try {
        if (token) {
            const usuario = await fetchConToken('/api/usuario');
            userName.textContent = usuario.nombre || '';
        }
        await obtenerProductos();
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
