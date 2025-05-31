// ------------------------------------------- Primer Menú: Inicio Sesión, Registro -----------------------------------------------
document.getElementById('hamburger').addEventListener('click', function() {
    var navLinks = document.getElementById('navLinks');
    navLinks.classList.toggle('show');
});

// ---------------------------------------------- Boton de Cancelar en Registro ---------------------------------------------------
function cancelarFormulario() {
    if (confirm('¿Estás seguro de que quieres cancelar? Todos los datos no guardados se perderán.')) {
        location.reload();
    }
}

// ------------------------ Icono visibilidad de contraseña en el Registro e Inicio de Sesión ---------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const contraseñaField = document.getElementById('contraseña');
    const confirmContraseñaField = document.getElementById('contraseña_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

    // Alternar visibilidad de la contraseña
    togglePassword.addEventListener('click', function() {
        const type = contraseñaField.type === 'password' ? 'text' : 'password';
        contraseñaField.type = type;
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });

    // Alternar visibilidad de la confirmación de contraseña (si el ícono existe)
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmContraseñaField.type === 'password' ? 'text' : 'password';
            confirmContraseñaField.type = type;
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    }
});