import '../css/app.css';

const gridProductos = document.getElementById("productosGrid");
const inputBusqueda = document.getElementById("busqueda");

let productos = [];
let seleccionados = [];

async function obtenerProductos() {
    const respuesta = await fetch("https://fakestoreapi.com/products");
    productos = await respuesta.json();

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
                    seleccionados.push({
                        id: producto.id,
                        title: producto.title,
                        price: producto.price,
                        category: producto.category,
                        image: producto.image
                    });
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

inputBusqueda.addEventListener("keyup", (evento) => {
    const texto = evento.target.value.toLowerCase();
    const filtrados = productos.filter(p =>
        p.title.toLowerCase().includes(texto)
    );

    mostrarProductos(filtrados.slice(0, 6));
});

obtenerProductos();

const formulario = document.getElementById("formularioDatos");
const nombre = document.getElementById("nombre");
const ocupacion = document.getElementById("ocupacion");
const correo = document.getElementById("correo");
const edad = document.getElementById("edad");
const tipoCaja = document.getElementById("tipoCaja");
const frecuenciaCaja = document.getElementById("frecuenciaCaja");

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
            errorSpan.textContent = "Solo se permiten letras y espacios, mínimo 6 caracteres.";
            wrapper.classList.add("error");
            valido = false;
        } else {
            errorSpan.textContent = "";
            wrapper.classList.remove("error");
        }
    }

    if (tipo === "correo") {
        if (!expresiones.correo.test(input.value.trim())) {
            errorSpan.textContent = "Ingresa un correo válido (ej: usuario@dominio.com).";
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
            errorSpan.textContent = "Ingresa un número válido entre 18 y 100.";
            wrapper.classList.add("error");
            valido = false;
        } else {
            errorSpan.textContent = "";
            wrapper.classList.remove("error");
        }
    }

    return valido;
}

[nombre, ocupacion, correo, edad].forEach(input => {
    input.addEventListener("input", () => {
        if (input === nombre || input === ocupacion) validarInput(input, "texto");
        if (input === correo) validarInput(input, "correo");
        if (input === edad) validarInput(input, "edad");
    });

    input.addEventListener("blur", () => {
        if (input === nombre || input === ocupacion) validarInput(input, "texto");
        if (input === correo) validarInput(input, "correo");
        if (input === edad) validarInput(input, "edad");
    });
});


formulario.addEventListener("submit", async (e) => {
    e.preventDefault();

    const nombreValido = validarInput(nombre, "texto");
    const ocupacionValido = validarInput(ocupacion, "texto");
    const correoValido = validarInput(correo, "correo");
    const edadValida = validarInput(edad, "edad");

    if (!nombreValido || !ocupacionValido || !correoValido || !edadValida) {
        Swal.fire({
            icon: 'warning',
            title: 'Corrige los errores en el formulario',
            confirmButtonColor: '#f0ad4e'
        });
        return;
    }

    if (seleccionados.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Selecciona al menos un producto',
            confirmButtonColor: '#f0ad4e'
        });
        return;
    }

    if (!tipoCaja.value || !frecuenciaCaja.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Selecciona tipo de caja y frecuencia',
            confirmButtonColor: '#f0ad4e'
        });
        return;
    }

    const datos = {
        nombre: nombre.value,
        ocupacion: ocupacion.value,
        correo: correo.value,
        edad: edad.value,
        tipoCaja: tipoCaja.value,
        frecuenciaCaja: frecuenciaCaja.value,
        productos: seleccionados.map(p => ({
            id: p.id,
            title: p.title,
            price: p.price,
            category: p.category,
            image: p.image
        }))
    };

    try {
        const respuesta = await fetch('/api/contacto', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();

        if (!respuesta.ok) {
            // Manejo de errores del backend
            if (respuesta.status === 422 && data.errors) {
                const errores = data.errors;
                Object.keys(errores).forEach(campo => {
                    const errorSpan = document.getElementById(`error-${campo}`);
                    if (errorSpan) {
                        errorSpan.textContent = errores[campo][0];
                        errorSpan.style.display = "block";
                        errorSpan.parentElement.classList.add("error");
                        setTimeout(() => {
                            errorSpan.style.display = "none";
                            errorSpan.textContent = "";
                            errorSpan.parentElement.classList.remove("error");
                        }, 5000);
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.mensaje || 'Hubo un error al enviar los datos',
                    confirmButtonColor: '#d33'
                });
            }
            return;
        }

        // Si todo salió bien
        Swal.fire({
            icon: 'success',
            title: '¡Perfecto!',
            text: data.mensaje || 'Datos enviados correctamente',
            confirmButtonColor: '#007bff'
        });

        formulario.reset();
        seleccionados = [];
        document.querySelectorAll(".product-card.selected").forEach(card => card.classList.remove("selected"));

    } catch (error) {
        console.error("Error al enviar datos:", error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Hubo un error al enviar los datos',
            confirmButtonColor: '#d33'
        });
    }
});

