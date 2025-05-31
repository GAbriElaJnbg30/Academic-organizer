// ------------------------------------ Para el apartado de actualizar perfil de usuario ----------------------------------------
// Función genérica para alternar la habilitación de un campo de actualizar
function toggleField(buttonId, fieldId) {
    const button = document.getElementById(buttonId);
    const field = document.getElementById(fieldId);

    if (button && field) {
        button.addEventListener('click', () => {
            field.disabled = !field.disabled; // Alternar la propiedad 'disabled'
        });
    }
}

// Llamar a la función genérica para cada campo
const fields = [
    { button: 'update_nombre', field: 'nombre' },
    { button: 'update_foto_perfil', field: 'foto_perfil' },
    { button: 'update_apellido', field: 'apellido' },
    { button: 'update_nombre_usuario', field: 'nombre_usuario' },
    { button: 'update_correo_electronico', field: 'correo_electronico' },
    { button: 'update_contraseña', field: 'contraseña' },
    { button: 'update_fecha_nacimiento', field: 'fecha_nacimiento' },
    { button: 'update_genero', field: 'genero' },
    { button: 'update_telefono', field: 'telefono' }
];

fields.forEach(({ button, field }) => toggleField(button, field));

// Manejo específico para la confirmación de la contraseña
const passwordFields = [
    { button: 'update_contraseña', field: 'contraseña_confirmation' },
    { button: 'update_confirmar_contraseña', field: 'contraseña_confirmation' }
];

passwordFields.forEach(({ button, field }) => toggleField(button, field));

// Iconos de visbilidad de contraseña
function togglePasswordVisibility(id) {
    var passwordField = document.getElementById(id);
    var icon = document.getElementById(id === 'contraseña' ? 'eye-icon' : 'eye-icon-confirm');

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        passwordField.type = "password";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}