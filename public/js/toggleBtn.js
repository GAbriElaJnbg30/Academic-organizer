document.addEventListener("DOMContentLoaded", function () {
    var modalCrear = document.getElementById("modal-crear");
    var btnCrear = document.getElementById("crear-carpeta-btn");
    var cancelarBotonCrear = document.getElementById("cancelar-btn");
    var spanCrear = document.getElementById("cerrar-modal-crear");
    var formulario = modalCrear.querySelector("form");

    // Mostrar el modal cuando el botón Crear Carpeta sea clickeado
    btnCrear.onclick = function () {
        modalCrear.style.display = "flex";
    }

    // Cuando el usuario haga clic en el botón Cancelar, cerrar el modal sin enviar el formulario
    cancelarBotonCrear.onclick = function (e) {
        e.preventDefault();  // Evitar la acción del formulario
        modalCrear.style.display = "none";  // Cerrar el modal
    }

    // Cuando el usuario haga clic fuera del modal, cerrarlo sin hacer nada
    window.addEventListener('click', function (event) {
        if (event.target === modalCrear) {
            modalCrear.style.display = "none";  // Cerrar el modal
        }
    });

    // Cerrar el modal al hacer clic en el botón de cerrar
    if (spanCrear) {
        spanCrear.onclick = function () {
            modalCrear.style.display = "none";  // Cerrar el modal
        }
    }

    // Aquí aseguramos que la validación solo se ejecute al enviar el formulario
    formulario.addEventListener("submit", function (e) {
        // Si el nombre de la carpeta está vacío, evitamos que se envíe el formulario
        var nombreCarpeta = document.getElementById("folder-name").value;
        if (!nombreCarpeta.trim()) {
            e.preventDefault();  // Evitar el envío del formulario si está vacío
            alert("Por favor, ingresa un nombre para la carpeta.");
        }
    });
});


// -------------------------------- Eliminar las subcarpetas -----------------------------------------
function eliminarSeleccionados() {
    const subcarpetas = Array.from(document.querySelectorAll('.checkbox-carpeta[name="subcarpetasSeleccionadas[]"]:checked'))
        .map(input => input.value);

    const archivos = Array.from(document.querySelectorAll('.checkbox-carpeta[name="archivosSeleccionados[]"]:checked'))
        .map(input => input.value);

    if (subcarpetas.length === 0 && archivos.length === 0) {
        alert('Selecciona al menos una subcarpeta o archivo para eliminar.');
        return;
    }

    if (!confirm('¿Deseas eliminar las subcarpetas y/o archivos seleccionados?')) {
        return;
    }

    // Enviar los datos al servidor para eliminación
    const contenedor = document.getElementById('contenedorInputsEliminar');
    contenedor.innerHTML = ''; // Limpiar contenedor antes de agregar inputs ocultos

    // Agregar subcarpetas seleccionadas al formulario
    subcarpetas.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'subcarpetasSeleccionadas[]';
        input.value = id;
        contenedor.appendChild(input);
    });

    // Agregar archivos seleccionados al formulario
    archivos.forEach(nombreArchivo => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'archivosSeleccionados[]';
        input.value = nombreArchivo;
        contenedor.appendChild(input);
    });

    // Enviar el formulario
    document.getElementById('formEliminarSeleccionados').submit();
}



// ------------------------------- Abrir el modal de actualizar ------------------------------
const modal = document.getElementById("miModal");
const btnAbrir = document.getElementById("btn-abrir-editar");
const btnCerrar = document.getElementById("cancelar-editar-btn");

btnAbrir.onclick = function () {
    const checkboxes = document.querySelectorAll('.checkbox-carpeta:checked');

    if (checkboxes.length === 0) {
        alert("Selecciona una subcarpeta para editar.");
        return;
    }

    if (checkboxes.length > 1) {
        alert("Solo puedes editar una subcarpeta a la vez.");
        return;
    }

    const checkbox = checkboxes[0];
    const idSeleccionado = checkbox.value;

    // Buscar el div padre que contiene el nombre de la carpeta
    const carpetaDiv = checkbox.closest('.carpeta') || checkbox.closest('.fila-carpeta');
    const nombreCarpeta = carpetaDiv.querySelector('.nombre-carpeta')?.innerText.trim();

    // Rellenar los campos del formulario
    document.getElementById('id-carpeta').value = idSeleccionado;
    document.getElementById('nuevo-nombre').value = nombreCarpeta;

    // Estilos del contenedor del modal (fondo)
    modal.style.display = 'flex'; // cambiar a flex
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
    modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    modal.style.zIndex = '1000';

    // Estilos del contenido interno (modal en sí)
    const modalContenido = modal.querySelector('.modal-contenido');
    if (modalContenido) {
        modal.style.display = 'flex'; // cambiar a flex
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.justifyContent = 'center';
        modal.style.alignItems = 'center';
        modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        modal.style.zIndex = '1000';
    }
}

// Cerrar el modal con botón cancelar
btnCerrar.onclick = function () {
    modal.style.display = "none";
}

// Cerrar modal si el usuario hace clic fuera de la ventana del modal
// Cerrar modal editar si el usuario hace clic fuera
window.addEventListener('click', function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

// Enviar el formulario
document.getElementById("form-editar").onsubmit = function (e) {
    // e.preventDefault(); <-- Solo si manejas AJAX, si usas Laravel normal, NO LO PONGAS
    // modal.style.display = "none"; <-- Igual, si haces AJAX. Si no, Laravel se encarga.
}

// ---------------------------------- Abrir el modal de Crear Carpeta --------------------------------
