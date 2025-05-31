


// ------------------------------------------ Vista: Lista o Mosaico ------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const customSelect = document.querySelector('.custom-select');
    const selectedOption = customSelect.querySelector('.selected-option');
    const optionsList = customSelect.querySelector('.options');
    const options = optionsList.querySelectorAll('.option');
    const contenedorCarpetas = document.querySelector('.contenedor-carpetas');

    // Obtener la vista guardada del localStorage
    const savedView = localStorage.getItem('vistaSeleccionada') || 'mosaico'; // Predeterminado a 'mosaico'
    contenedorCarpetas.className = `contenedor-carpetas ${savedView}`;

    // Actualizar la selección visual en el selector
    const activeOption = Array.from(options).find(option => option.getAttribute('data-value') === savedView);
    if (activeOption) {
        const icon = activeOption.querySelector('i').className;
        const text = activeOption.querySelector('span').textContent;
        selectedOption.innerHTML = `<i class="${icon}"></i><span>${text}</span><i class="fas fa-chevron-down arrow"></i>`;
        activeOption.classList.add('active');
    }

    // Alternar menú de opciones
    selectedOption.addEventListener('click', () => {
        optionsList.style.display = optionsList.style.display === 'block' ? 'none' : 'block';
    });

    // Manejar selección
    options.forEach(option => {
        option.addEventListener('click', () => {
            const viewType = option.getAttribute('data-value'); // 'lista' o 'mosaico'

            // Cambiar clase del contenedor
            contenedorCarpetas.className = `contenedor-carpetas ${viewType}`;

            // Guardar la preferencia en localStorage
            localStorage.setItem('vistaSeleccionada', viewType);

            // Actualizar la opción seleccionada
            const icon = option.querySelector('i').className;
            const text = option.querySelector('span').textContent;
            selectedOption.innerHTML = `<i class="${icon}"></i><span>${text}</span><i class="fas fa-chevron-down arrow"></i>`;

            // Ocultar menú de opciones
            optionsList.style.display = 'none';

            // Actualizar clase activa
            options.forEach(opt => opt.classList.remove('active'));
            option.classList.add('active');
        });
    });

    // Ocultar el menú al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!customSelect.contains(e.target)) {
            optionsList.style.display = 'none';
        }
    });
});


// ------------------------------------------- Seleccionar Archivos --------------------------------------------
document.addEventListener("DOMContentLoaded", function () {
    const seleccionarArchivosBtn = document.querySelector(".btn-seleccionar-archivos");
    const mensajeSeleccion = document.getElementById("mensaje-seleccion");
    const contadorSeleccionados = document.getElementById("selected-count"); // Contador de seleccionados

    // Función para alternar la visibilidad de los checkboxes y limpiar la selección
    function alternarCheckboxes() {
        const checkboxes = document.querySelectorAll(".checkbox-carpeta");
        let anySelected = false;

        // Verificar si hay algún checkbox marcado
        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                anySelected = true;
            }
        });

        // Alternar visibilidad y desmarcar los checkboxes si están visibles
        checkboxes.forEach((checkbox) => {
            checkbox.style.display = checkbox.style.display === "none" ? "block" : "none";
            if (checkbox.checked) {
                checkbox.checked = false; // Desmarcar los checkboxes
            }
        });

        // Si se está cerrando la selección, limpiar el mensaje y la selección
        if (anySelected) {
            mensajeSeleccion.style.display = "none";
            mensajeSeleccion.textContent = ""; // Limpiar el contenido del mensaje
        }

        // Reiniciar el contador
        contadorSeleccionados.textContent = "0";
    }

    // Función para actualizar el mensaje de carpetas seleccionadas
    function actualizarMensajeSeleccion() {
        const checkboxes = document.querySelectorAll(".checkbox-carpeta");
        const carpetasSeleccionadas = Array.from(checkboxes)
            .filter((checkbox) => checkbox.checked)
            .map((checkbox) => {
                // Busca el contenedor padre correcto según la vista
                const contenedor = checkbox.closest(".carpeta") || checkbox.closest(".fila-carpeta");
                return contenedor.dataset.id_carpeta;
            });

        // Actualizar mensaje de carpetas seleccionadas
        if (carpetasSeleccionadas.length > 0) {
            mensajeSeleccion.style.display = "block";
            mensajeSeleccion.textContent = `Se seleccionaron las carpetas con ID: ${carpetasSeleccionadas.join(", ")}`;
        } else {
            mensajeSeleccion.style.display = "none";
        }

        // Actualizar el contador de seleccionados
        contadorSeleccionados.textContent = carpetasSeleccionadas.length;
    }

    // Asociar eventos
    seleccionarArchivosBtn.addEventListener("click", alternarCheckboxes);

    document.addEventListener("change", function (event) {
        if (event.target.classList.contains("checkbox-carpeta")) {
            actualizarMensajeSeleccion();
        }
    });
});


// ------------------------------------------ Contenido de la Carpeta --------------------------------------------------------
document.addEventListener("DOMContentLoaded", function () {
    const carpetas = document.querySelectorAll(".carpeta, .fila-carpeta");

    // Agregar el evento de doble clic a cada carpeta
    carpetas.forEach((carpeta) => {
        carpeta.addEventListener("dblclick", function () {
            const carpetaId = carpeta.dataset.id_carpeta; // Obtener el ID de la carpeta
            abrirCarpeta(carpetaId); // Llamar a la función para abrir la carpeta
        });
    });

    // Función para abrir una carpeta
    function abrirCarpeta(idCarpeta) {
        // Redirigir a una nueva página o cargar contenido dinámicamente
        // Ejemplo: Redirigir a otra ruta
        window.location.href = `/sitioweb/public/carpeta/${idCarpeta}/contenido`;
        
        // O puedes cargar el contenido dinámicamente mediante una solicitud AJAX
        /*
        fetch(`/carpetas/${idCarpeta}/contenido`)
            .then(response => response.json())
            .then(data => {
                // Aquí puedes actualizar el DOM con el contenido de la carpeta
                console.log("Contenido de la carpeta:", data);
            })
            .catch(error => {
                console.error("Error al cargar el contenido de la carpeta:", error);
            });
        */
    }
});

// --------------------------------------------- Sub carpetas ---------------------------------------------
// Obtener el modal y el botón

