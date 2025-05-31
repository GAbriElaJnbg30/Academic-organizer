// -------------------------- Eliminar carpetas Padre con todo su contenido --------------------------------
function enviarFormularioEliminar() {
    const checkboxes = document.querySelectorAll('.checkbox-carpeta:checked');
    if (checkboxes.length === 0) {
        alert('Por favor, selecciona al menos una carpeta para eliminar.');
        return;
    }

    if (!confirm('¿Estás seguro de que deseas eliminar las carpetas seleccionadas y todo su contenido?')) {
        return;
    }

    const contenedor = document.getElementById('contenedorInputsSeleccionados');
    contenedor.innerHTML = ''; // Limpiar inputs anteriores

    checkboxes.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'carpetasSeleccionadas[]';
        input.value = cb.value;
        contenedor.appendChild(input);
    });

    document.getElementById('formEliminarCarpetas').submit();
}

// ----------------------------------- MOSTRAR MODAL ACTUALIZAR ---------------------------------
let idCarpetaSeleccionada = null;
let nombreCarpetaSeleccionada = null;

// Marcar la carpeta seleccionada visualmente
document.querySelectorAll('.carpeta').forEach(carpeta => {
    carpeta.addEventListener('click', (event) => {
        // Si ya hay una carpeta seleccionada y se intenta seleccionar otra distinta
        const yaSeleccionada = document.querySelector('.carpeta.seleccionada');



        // Quitar selección de todas y aplicar solo a la actual
        document.querySelectorAll('.carpeta').forEach(c => c.classList.remove('seleccionada'));
        carpeta.classList.add('seleccionada');

        idCarpetaSeleccionada = carpeta.dataset.id_carpeta;
        nombreCarpetaSeleccionada = carpeta.querySelector('.nombre-carpeta').textContent.trim();

        // Mostrar el botón para abrir el modal
        document.getElementById('btn-abrir-editar').style.display = 'inline-block';
        // Habilitar botón de subir archivo
        document.getElementById('subir-archivo-btn').disabled = false;
    });
});

// Mostrar el modal de editar nombre
document.getElementById('btn-abrir-editar').addEventListener('click', () => {
    if (idCarpetaSeleccionada) {
        const modal = document.getElementById('modal-editar-nombre');
        document.getElementById('id-carpeta').value = idCarpetaSeleccionada;
        document.getElementById('nuevo-nombre').value = nombreCarpetaSeleccionada;
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
    } else {
        alert('Por favor, selecciona primero una carpeta para editar.');
    }
});

// Ocultar el modal al cancelar
document.getElementById('cancelar-editar-btn').addEventListener('click', () => {
    document.getElementById('modal-editar-nombre').style.display = 'none';
});




// ------------------------- FUNCIONALIDAD PARA SUBIR ARCHIVOS -------------------------
const botonSubir = document.getElementById('subir-archivo-btn');
const inputArchivo = document.getElementById('archivo');
let idCarpetaSeleccionadaArchivo = null;


document.querySelectorAll('.carpeta').forEach(carpeta => {
    carpeta.addEventListener('click', (event) => {
        // Evitar conflicto si haces clic en checkbox dentro de la carpeta
        if (event.target.classList.contains('checkbox-carpeta')) return;

        const yaSeleccionada = document.querySelector('.carpeta.seleccionada');

        if (yaSeleccionada && yaSeleccionada !== carpeta) {
            alert('Solo puedes seleccionar una carpeta a la vez.');
            return;
        }

        // Quitar selección anterior
        document.querySelectorAll('.carpeta').forEach(c => c.classList.remove('seleccionada'));
        carpeta.classList.add('seleccionada');

        idCarpetaSeleccionadaArchivo = carpeta.dataset.id_carpeta;

        // Mostrar en consola la carpeta seleccionada
        const nombreCarpeta = carpeta.querySelector('.nombre-carpeta').textContent.trim();
        console.log(`Carpeta seleccionada: ${nombreCarpeta} (ID: ${idCarpetaSeleccionadaArchivo})`);

        // Habilitar botón de subir archivo
        botonSubir.disabled = false;
    });
});


// Manejar clic en el botón "Subir Archivo"
botonSubir.addEventListener('click', () => {
    const seleccionadas = document.querySelectorAll('.carpeta.seleccionada');

    if (seleccionadas.length === 0) {
        alert('Por favor, selecciona primero una carpeta.');
        return;
    }

    if (seleccionadas.length > 1) {
        alert('Solo puedes seleccionar una carpeta.');
        return;
    }

    // Si todo bien, abrir el explorador de archivos
    inputArchivo.click();
});

// Manejar selección del archivo
// Manejar selección del archivo
inputArchivo.addEventListener('change', function () {
    if (inputArchivo.files.length > 0) {
        // Obtener la carpeta seleccionada
        const carpetaSeleccionada = document.querySelector('.carpeta.seleccionada');

        if (carpetaSeleccionada) {
            const nombreCarpeta = carpetaSeleccionada.querySelector('.nombre-carpeta').textContent.trim();
            console.log('Nombre de la carpeta seleccionada:', nombreCarpeta);
        }

        console.log('Archivos seleccionados:');
        for (let i = 0; i < inputArchivo.files.length; i++) {
            console.log('- ' + inputArchivo.files[i].name);
        }

        document.getElementById('id-carpeta').value = idCarpetaSeleccionadaArchivo;
        document.getElementById('formSubirArchivos').submit();
    }
});



// Guarda el ID se la carpeta seleccionada




