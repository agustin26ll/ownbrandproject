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
    
    if (seleccionados.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Selecciona al menos un producto',
            confirmButtonColor: '#f0ad4e'
        });
        return;
    }

    const datos = {
        nombre: document.getElementById("nombre").value,
        ocupacion: document.getElementById("ocupacion").value,
        correo: document.getElementById("correo").value,
        edad: document.getElementById("edad").value,
        productos: seleccionados
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

        const text = await respuesta.text();
        console.log(text);
        let data;
        try {
            data = JSON.parse(text);
        } catch {
            console.error('No es JSON:', text);
        }

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
