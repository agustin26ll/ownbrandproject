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
            if (seleccionados.includes(producto.id)) {
                seleccionados = seleccionados.filter(id => id !== producto.id);
                tarjeta.classList.remove("selected");
            } else {
                if (seleccionados.length < 4) {
                    seleccionados.push(producto.id);
                    tarjeta.classList.add("selected");
                } else {
                    alert("Máximo 4 productos seleccionados");
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

formulario.addEventListener("submit", async (e) => {
    e.preventDefault();

    const datos = {
        nombre: document.getElementById("nombre").value,
        ocupacion: document.getElementById("ocupacion").value,
        correo: document.getElementById("correo").value,
        edad: document.getElementById("edad").value
    };

    try {
        const respuesta = await fetch('/api/contacto', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();

        Swal.fire({
            icon: 'success',
            title: '¡Perfecto!',
            text: data.mensaje || 'Datos enviados correctamente',
            confirmButtonColor: '#007bff'
        });

        formulario.reset();
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



