import '../css/registro.css';

const formulario = document.getElementById("formularioDatos");
const nombre = document.getElementById("nombre");
const correo = document.getElementById("correo");
const contrasenia = document.getElementById("contrasenia");
const edad = document.getElementById("edad");
const togglePassword = document.getElementById("togglePassword");

const expresiones = {
    texto: /^[a-zA-ZÀ-ÿ\s]{6,40}$/,
    correo: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    contrasenia: /^.{6,20}$/
};

function validarInput(input, tipo) {
    let valido = true;
    let mensaje = "";

    if (tipo === "texto" && !expresiones.texto.test(input.value.trim())) {
        mensaje = "Solo letras y espacios, mínimo 6 caracteres.";
        valido = false;
    }
    if (tipo === "correo" && !expresiones.correo.test(input.value.trim())) {
        mensaje = "Correo inválido (ej: usuario@dominio.com).";
        valido = false;
    }
    if (tipo === "contrasenia" && !expresiones.contrasenia.test(input.value.trim())) {
        mensaje = "La contraseña debe tener entre 6 y 20 caracteres.";
        valido = false;
    }
    if (tipo === "edad") {
        const valor = parseInt(input.value, 10);
        if (isNaN(valor) || valor < 18 || valor > 100) {
            mensaje = "Edad válida entre 18 y 100.";
            valido = false;
        }
    }

    const wrapper = input.parentElement;
    const errorSpan = wrapper.querySelector(".error-message");

    if (!valido) {
        errorSpan.textContent = mensaje;
        wrapper.classList.add("error");
    } else {
        wrapper.classList.remove("error");
        errorSpan.textContent = "";
    }

    return valido;
}

togglePassword.addEventListener("click", () => {
    const tipo = contrasenia.getAttribute("type") === "password" ? "text" : "password";
    contrasenia.setAttribute("type", tipo);
    togglePassword.classList.toggle("fa-eye-slash");
});

[nombre, correo, contrasenia, edad].forEach(input => {
    input.addEventListener("input", () => {
        if (input === nombre) validarInput(input, "texto");
        if (input === correo) validarInput(input, "correo");
        if (input === contrasenia) validarInput(input, "contrasenia");
        if (input === edad) validarInput(input, "edad");
    });
    input.addEventListener("blur", () => {
        if (input === nombre) validarInput(input, "texto");
        if (input === correo) validarInput(input, "correo");
        if (input === contrasenia) validarInput(input, "contrasenia");
        if (input === edad) validarInput(input, "edad");
    });
});

formulario.addEventListener("submit", async (e) => {
    e.preventDefault();

    const nombreValido = validarInput(nombre, "texto");
    const correoValido = validarInput(correo, "correo");
    const contraseniaValida = validarInput(contrasenia, "contrasenia");
    const edadValida = validarInput(edad, "edad");

    if (!nombreValido || !correoValido || !contraseniaValida || !edadValida) {
        Swal.fire({
            icon: 'warning',
            title: 'Corrige los errores en el formulario',
            confirmButtonColor: '#f0ad4e'
        });
        return;
    }

    const datos = {
        nombre: nombre.value.trim(),
        correo: correo.value.trim(),
        contrasenia: contrasenia.value,
        edad: parseInt(edad.value, 10)
    };

    try {
        const respuesta = await fetch('/api/registro', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();

        if (!respuesta.ok) {
            if (respuesta.status === 422 && data.errors) {
                Object.keys(data.errors).forEach(campo => {
                    const input = document.getElementById(campo);
                    const wrapper = input.parentElement;
                    const errorSpan = wrapper.querySelector(".error-message");
                    if (errorSpan) {
                        errorSpan.textContent = data.errors[campo][0];
                        wrapper.classList.add("error");
                        setTimeout(() => {
                            errorSpan.textContent = "";
                            wrapper.classList.remove("error");
                        }, 5000);
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.mensaje || 'Error al registrar',
                    confirmButtonColor: '#d33'
                });
            }
            return;
        }

        Swal.fire({
            icon: 'success',
            title: '¡Registro exitoso!',
            text: data.mensaje || 'Tu cuenta ha sido creada',
            confirmButtonColor: '#007bff'
        });

        formulario.reset();
        [nombre, correo, contrasenia, edad].forEach(input => {
            input.parentElement.classList.remove("error");
            input.parentElement.querySelector(".error-message").textContent = "";
        });

    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Error al enviar los datos',
            confirmButtonColor: '#d33'
        });
    }
});
