import '../css/login.css';

const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#contrasenia");

togglePassword.addEventListener("click", () => {
    const type = password.type === "password" ? "text" : "password";
    password.type = type;
    togglePassword.classList.toggle("fa-eye");
    togglePassword.classList.toggle("fa-eye-slash");
});

const formulario = document.getElementById("formularioDatos");

formulario.addEventListener("submit", async (e) => {
    e.preventDefault();

    const datos = {
        correo: document.getElementById("correo").value.trim(),
        contrasenia: document.getElementById("contrasenia").value
    };

    try {
        const respuesta = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();
        console.log(data);
        
        if (respuesta.status === 422) {
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: data.mensaje,
                confirmButtonColor: '#f0ad4e'
            });
            return;
        }

        if (!respuesta.ok) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.mensaje || 'Error al iniciar sesión',
                confirmButtonColor: '#d33'
            });
            return;
        }

        localStorage.setItem('token', data.token);

        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido!',
            text: data.mensaje,
            confirmButtonColor: '#007bff'
        }).then(() => {
            window.location.href = '/perfil';
        });

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
