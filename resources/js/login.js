import '../css/login.css';

const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");

togglePassword.addEventListener("click", () => {
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);

    togglePassword.classList.toggle("fa-eye");
    togglePassword.classList.toggle("fa-eye-slash");
});

const formulario = document.getElementById("formularioDatos");

formulario.addEventListener("submit", async (e) => {
    e.preventDefault();

    const datos = {
        correo: document.getElementById("correo").value,
        contrasenia: document.getElementById("contrasenia").value,
    };

    try {
        const respuesta = await fetch('/api/loging', {
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