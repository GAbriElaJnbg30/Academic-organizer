@php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloc De Notas</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/bloc.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Para las fuentes -->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto&family=Open+Sans&family=Montserrat&family=Lato&family=Raleway&family=Poppins&family=Oswald&family=Merriweather&display=swap"
        rel="stylesheet">

</head>

<body>
    <div class="logos">
        <div class="left-logos">
            <a href="https://www.sep.gob.mx" target="_blank"><img src="{{ asset('imagenes/sep.png') }}" alt="Imagen 1"
                    class="sep"></a>
            <a href="https://www.tecnm.mx" target="_blank"><img src="{{ asset('imagenes/tecnm.png') }}" alt="Imagen 2"
                    class="tecnm"></a>
            <a href="https://leon.tecnm.mx" target="_blank"><img src="{{ asset('imagenes/itl.png') }}" alt="Imagen 3"
                    class="itl"></a>
        </div>
        <a href="{{ route('iniciarsesion') }}"><img src="{{ asset('imagenes/ao.png') }}" alt="Imagen 4"
                class="logo"></a>
    </div>

    <div class="menu-bar">
        <div class="hamburger-menu" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>

        @auth
        <div class="datos">
            <span class="datos_usuario">{{ session('nombre') }} {{ session('apellido') }}</span>
            <div class="user-info">
                <!-- Mostrar la foto de perfil, si existe -->
                @if(session('foto_perfil'))
                <a href="{{ route('uactperfil') }}">
                    <img src="{{ asset('storage/' . session('foto_perfil')) }}" class="user-photo" id="user-photo">
                </a>
                @else
                <!-- Si no tiene foto de perfil, mostrar una imagen por defecto o el logo -->
                <a href="{{ route('uactperfil') }}">
                    <img src="{{ asset('imagenes/perfil/foto.jpg') }}" class="user-photo" id="user-photo">
                </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmarCierreSesion()">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt logout-icon"></i>
                        Cerrar Sesión
                    </button>
                </form>

                <script>
                function confirmarCierreSesion() {
                    return confirm('¿Estás seguro de que deseas cerrar sesión?');
                }
                </script>
            </div>
        </div>
    </div>

    <div class="side-menu" id="sideMenu">
        <a href="{{ route('ubienvenida') }}"><i class="fas fa-home"></i> Inicio</a>
        <a href="{{ route('archivos') }}"><i class="fas fa-folder-open"></i> Gestión De Archivos</a>
        <a href="{{ route('notas') }}"><i class="fas fa-tasks"></i> Bloc De Notas</a>
        <a href="{{ route('recordatorios') }}"><i class="fas fa-bell"></i> Recordatorios</a>
        <a href="{{ route('uactperfil') }}"><i class="fas fa-user"></i> Perfil</a>
    </div>

    <div class="main-content" id="mainContent">
        <div class="container">
            <!-- Top Navigation Bar -->
            <div class="top-nav">

                <a href="{{ route('notas') }}" class="btn back-btn"
                    onclick="event.preventDefault(); handleBackClick();">
                    <i class="fas fa-arrow-left"></i> Back
                </a>



                <!-- Botón que abre el modal de guardar -->
                <button type="button" class="btn save-btn" onclick="openModal()">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Note Editor Panel -->
                <div class="note-panel">

                    <div class="note-container" id="nota">
                        <div id="editorNota" class="notas" contenteditable="true">
                            {!! $nota->contenido_html ?? 'Sin contenido' !!}
                        </div>
                    </div>
                </div>

                <!-- Formatting Controls Panel -->
                <div class="controls-panel">
                    <!-- Row 1: Font Controls -->
                    <div class="control-row">
                        <select class="font-family-select">
                            <option value="" disabled selected>Font</option>
                            <option value="Arial, sans-serif">Arial</option>
                            <option value="'Times New Roman', serif">Times New Roman</option>
                            <option value="Calibri, sans-serif">Calibri</option>
                            <option value="Roboto, sans-serif">Roboto</option>
                            <option value="'Open Sans', sans-serif">Open Sans</option>
                            <option value="'Montserrat', sans-serif">Montserrat</option>
                            <option value="'Lato', sans-serif">Lato</option>
                            <option value="'Raleway', sans-serif">Raleway</option>
                            <option value="'Poppins', sans-serif">Poppins</option>
                            <option value="'Oswald', sans-serif">Oswald</option>
                            <option value="'Merriweather', serif">Merriweather</option>
                        </select>

                        <select class="font-size-select">
                            <option value="" disabled selected>Size</option>
                            <option value="8">8</option>
                            <option value="10">10</option>
                            <option value="12">12</option>
                            <option value="14">14</option>
                            <option value="16">16</option>
                            <option value="18">18</option>
                            <option value="20">20</option>
                            <option value="24">24</option>
                        </select>

                        <button class="btn icon-btn" id="boldBtn" title="Bold"><i class="fas fa-bold"></i></button>
                        <button class="btn icon-btn" id="italicBtn" title="Italic"><i
                                class="fas fa-italic"></i></button>
                        <button class="btn icon-btn" id="underlineBtn" title="Underline"><i
                                class="fas fa-underline"></i></button>

                    </div>

                    <!-- Row 2: Color Controls -->
                    <div class="control-row">
                        <div class="color-picker">
                            <button class="btn color-btn" title="Text Color">
                                <i class="fas fa-font"></i>
                                <input type="color" id="colorTexto" class="color-input">
                            </button>
                        </div>

                        <div class="color-picker">
                            <button id="highlightBtn" class="btn color-btn" title="Highlight Text">
                                <i class="fas fa-highlighter"></i>
                                <input type="color" id="colorMarcado" class="color-input" value="#ffff00">
                            </button>
                        </div>

                        <div class="color-picker">
                            <!-- Botón para borrar resaltado -->
                            <button id="clearHighlightBtn" class="btn color-btn" title="Clear Highlight">
                                <i class="fas fa-eraser"></i>
                            </button>
                        </div>



                        <!-- New: Note Space Background Color -->
                        <div class="color-picker">
                            <button class="btn color-btn" title="Note Background Color">
                                <i class="fas fa-fill-drip" id="colorIcono"></i>
                                <input type="color" id="colorRelleno" class="color-input" value="#ffffff">
                            </button>
                        </div>

                    </div>

                    <!-- Row 3: Alignment Controls -->
                    <div class="control-row">
                        <button class="btn icon-btn" title="Align Left" id="alignLeft"><i
                                class="fas fa-align-left"></i></button>
                        <button class="btn icon-btn" title="Align Center" id="alignCenter"><i
                                class="fas fa-align-center"></i></button>
                        <button class="btn icon-btn" title="Align Right" id="alignRight"><i
                                class="fas fa-align-right"></i></button>
                        <button class="btn icon-btn" title="Justify" id="alignJustify"><i
                                class="fas fa-align-justify"></i></button>

                        <!-- Botón para lista numerada -->
                        <button class="btn icon-btn" title="Numeros" id="orderedList">
                            <i class="fas fa-list-ol"></i> <!-- Icono para lista numerada -->
                        </button>

                        <!-- Botón para lista con viñetas (puntos) -->
                        <button class="btn icon-btn" title="Viñetas (Puntos)" id="unorderedList">
                            <i class="fas fa-list-ul"></i> <!-- Icono para lista con viñetas (puntos) -->
                        </button>

                    </div>

                    <!-- Row 4: Voice Controls -->
                    <div class="control-row">
                        <button class="btn" title="Voice to Text" id="voiceBtn"><i class="fas fa-microphone"></i>
                            Voice</button>
                    </div>

                    <!-- Row 5: Image Controls -->
                    <div class="control-row">
                        <button class="btn" id="addImageBtn" title="Add Image">
                            <i class="fas fa-image"></i> Add Image
                        </button>

                        <input type="file" id="imageInput" accept="image/*" style="display: none;" />

                        <select class="image-size-select" id="imageSizeSelect">
                            <option value="" disabled selected>Image Size</option>
                            <option value="xsmall">Extra Small</option>
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                            <option value="original">Original</option>
                        </select>

                        <button class="btn" id="deleteImageBtn" title="Delete Image">
                            <i class="fas fa-trash"></i> Delete Image
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal oculto al inicio -->
        <div id="noteModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Guardar Nota</h2>
                <form id="noteForm" action="{{ url('/notas') }}" method="POST">
                    @csrf
                    <!-- Token CSRF de Laravel -->
                    <div class="input-wrapper">
                        <i class="fas fa-folder"></i> <!-- Ícono de carpeta -->
                        <input type="hidden" name="id_nota" id="id_nota" value="{{ $nota->id_nota ?? '' }}">

                        <input type="text" id="noteName" name="nombre_nota" class="modalGuardar"
                            placeholder="Nombre de la nota" value="{{ isset($nota) ? $nota->nombre_nota : '' }}"
                            required />
                    </div>

                    <!-- Campo oculto para el contenido HTML -->
                    <textarea name="contenido_html" id="contenido_html" style="display: none;"></textarea>

                    <div class="modal-buttons">
                        <button type="submit" id="btnGuardar">Guardar</button>
                        <button type="button" onclick="closeModal()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer" id="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Información de Contacto</h3>
                <ul>
                    <li><i class="fas fa-phone"></i> Teléfono: +34 123456789</li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        Email:
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=academicorganizersoporte@gmail.com"
                            target="_blank" rel="noopener noreferrer">
                            academicorganizersoporte@gmail.com
                        </a>
                    </li>
                    <li><i class="fas fa-map-marker-alt"></i> Dirección: Calle Ejemplo, 123</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Enlaces</h3>
                <ul>
                    <li><a href="{{ route('iniciarsesion') }}" target="_blank">Inicio</a></li>
                    <li><a href="{{ route('acercade') }}" target="_blank">Acerca de</a></li>
                    <li><a href="{{ route('manualusuario') }}" target="_blank">Manual Usuario</a></li>
                    <li><a href="{{ route('contacto') }}" target="_blank">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Redes Sociales</h3>
                <ul>
                    <li><a href="https://www.facebook.com/AcademicOrganizer" target="_blank"><i
                                class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="https://twitter.com/AcademicOrg" target="_blank"><i class="fab fa-twitter"></i>
                            Twitter</a></li>
                    <li><a href="https://www.instagram.com/AcademicOrganizer" target="_blank"><i
                                class="fab fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 ACADEMIC ORGANIZER. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!----------------------------------------------------------- JS ------------------------------------------------------------->
    <script src="{{ asset('js/menus.js') }}"></script>
    <script src="{{ asset('js/notas.js') }}"></script>

    @else
    <script>
    window.location.href = "{{ route('iniciarsesion') }}";
    </script>
    @endauth

    <script>
    // ----------------------- Guadar el contenido de la nota ------------------------
    document.addEventListener('DOMContentLoaded', function() {
        const editor = document.getElementById('editorNota');
        const contenido = `{!! $nota->contenido_html ?? '' !!}`;
        editor.innerHTML = contenido; // Asigna el contenido HTML al editor
    });

    // Asegúrate de que el formulario guarde el contenido del editor
    document.getElementById('noteForm').addEventListener('submit', function(e) {
        const editorContent = document.getElementById('editorNota').innerHTML;

        // Verifica que haya contenido
        if (!editorContent.trim()) {
            alert('Por favor, ingresa contenido en la nota.');
            e.preventDefault(); // Evita el envío del formulario si está vacío
            return;
        }

        // Asigna el contenido HTML al campo oculto
        document.getElementById('contenido_html').value = editorContent;
    });


    function openModal() {
        document.getElementById('noteModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('noteModal').style.display = 'none';
    }

    function saveNote() {
        const name = document.getElementById('noteName').value.trim();
        if (name === "") {
            alert("Por favor, ingresa un nombre para la nota.");
            return;
        }

        const data = {
            nombre_nota: name,
            tipo: 'texto', // Puedes cambiar esto según tu lógica
            ruta_nota: '/notas/' + name + '.txt', // Ejemplo de ruta
            fecha_modificacion: new Date().toISOString().split('T')[0] // Solo fecha YYYY-MM-DD
        };

        fetch('/api/notas', { // usa '/notas' si estás usando web.php
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Si estás usando web.php, descomenta esta línea y agrega el token CSRF
                    // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        console.error("Detalles del error:", errorData); // Mostrar detalles de error
                        throw new Error(errorData.message || "Error desconocido del servidor.");
                    });
                }
                return response.json();
            })
            .then(result => {
                console.log("Nota guardada correctamente:", result);
                alert("Nota guardada con éxito.");
                closeModal();
                document.getElementById('noteName').value = ""; // Limpia el input
            })
            .catch(error => {
                console.error("Error al guardar la nota:", error); // Muestra el error detallado
                alert("Hubo un error al guardar la nota: " + error.message); // Alerta con el mensaje del error
            });
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Elementos principales
        const nota = document.querySelector(".notas");
        const notaContainer = document.getElementById("nota");
        const editorNota = document.getElementById("editorNota");

        // Verificar que los elementos existen
        if (!nota || !notaContainer || !editorNota) {
            console.error("No se encontraron los elementos necesarios");
            return;
        }

        // Variables globales
        let fuenteActiva = "Arial, sans-serif";
        let tamanoActivo = "12";
        let isDrawing = false;
        let drawingTool = null; // 'pencil', 'marker', 'eraser'
        let canvas = null;
        let ctx = null;
        let lastX = 0;
        let lastY = 0;
        let selectedImage = null;
        let lastTimeout = null;
        // let lastSelection = null; // Para guardar la última posición del cursor


























        let isLeaving = false; // Flag para saber si estamos saliendo voluntariamente

        function getCurrentNoteId() {
            const notaIdElement = document.querySelector('.note-panel p');
            if (notaIdElement) {
                const idText = notaIdElement.textContent;
                const match = idText.match(/:\s*(\d+|null)/);
                if (match && match[1] !== 'null') {
                    return match[1];
                }
            }
            return window.location.pathname; // fallback
        }

        function saveToSessionStorage() {
            const noteId = getCurrentNoteId();
            if (!noteId) return;

            const bgColor = notaContainer.style.backgroundColor || document.getElementById("colorRelleno")
                ?.value || "#ffffff";
            sessionStorage.setItem(`nota_content_${noteId}`, nota.innerHTML);
            sessionStorage.setItem(`nota_bgcolor_${noteId}`, bgColor);

            console.log("💾 Contenido guardado en sessionStorage para nota:", noteId);
        }

        function loadFromSessionStorage() {
            const noteId = getCurrentNoteId();
            if (!noteId) return false;

            // 🧼 Si hay un flag de limpieza, no cargues nada
            if (sessionStorage.getItem("nota_clear_next_load") === "true") {
                clearTempNote(noteId);
                sessionStorage.removeItem("nota_clear_next_load"); // Solo se ejecuta una vez
                console.log("🧼 Limpieza: contenido temporal NO restaurado.");
                return false;
            }

            const savedContent = sessionStorage.getItem(`nota_content_${noteId}`);
            const savedBgColor = sessionStorage.getItem(`nota_bgcolor_${noteId}`);

            if (savedContent) {
                nota.innerHTML = savedContent;
                console.log("🔁 Contenido restaurado desde sessionStorage para nota:", noteId);
                setTimeout(makeImagesSelectable, 100);
                if (savedBgColor) {
                    notaContainer.style.backgroundColor = savedBgColor;
                    const colorInput = document.getElementById("colorRelleno");
                    if (colorInput) colorInput.value = savedBgColor;
                }
                return true;
            }
            return false;
        }

        function clearTempNote(noteId) {
            if (!noteId) return;
            sessionStorage.removeItem(`nota_content_${noteId}`);
            sessionStorage.removeItem(`nota_bgcolor_${noteId}`);
            console.log("🧹 Contenido temporal eliminado para nota:", noteId);
        }

        // ✅ Función que se llama al hacer clic en "Volver"
        window.handleBackClick = function() {
            const noteId = getCurrentNoteId();

            // Preguntar al usuario si desea salir sin guardar cambios
            const userConfirmed = window.confirm(
                "¿Estás seguro de que quieres salir? Los cambios no se guardarán.");

            if (userConfirmed) {
                isLeaving = true; // Evita que se guarde al salir
                if (noteId) {
                    clearTempNote(noteId);
                }

                // Flag para que no se restaure en la próxima carga
                sessionStorage.setItem("nota_clear_next_load", "true");

                console.log("🔙 Volviendo a la lista: contenido temporal eliminado.");
                window.location.href = "{{ route('notas') }}"; // Redirigir
            } else {
                console.log("🚫 El usuario canceló la salida.");
            }
        };

        window.addEventListener("DOMContentLoaded", function() {
            const currentNoteId = getCurrentNoteId();
            loadFromSessionStorage(); // Solo carga si no está el flag de limpieza
        });

        // ✅ Guardar solo si no estamos saliendo con Back
        window.addEventListener("pagehide", function() {
            if (!isLeaving) {
                saveToSessionStorage();
            } else {
                console.log("🚫 No se guarda porque el usuario está saliendo con 'Back'.");
            }
        });

        document.getElementById("btnGuardar").addEventListener("click", function() {
            const noteId = getCurrentNoteId();

            // Eliminar la nota temporal del sessionStorage
            if (noteId) {
                clearTempNote(noteId);
                console.log("💾 Nota temporal eliminada al guardar.");
            }

            // Flag para que no se restaure al recargar después del guardado
            sessionStorage.setItem("nota_clear_next_load", "true");
        });






















        // ------------------------------ INICIALIZACIÓN ------------------------------
        const loadedFromSession = loadFromSessionStorage();

        // Si no hay contenido en sessionStorage, inicializar con contenido existente
        if (!loadedFromSession && editorNota.innerHTML && editorNota.innerHTML !== 'Sin contenido') {
            // Inicializar editor con contenido existente
            //if (editorNota.innerHTML && editorNota.innerHTML !== 'Sin contenido') {
            nota.innerHTML = editorNota.innerHTML;

            // Extraer color de fondo si está guardado en un atributo data
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = editorNota.innerHTML;
            const wrapper = tempDiv.querySelector('[data-bg-color]');

            if (wrapper) {
                const bgColor = wrapper.getAttribute('data-bg-color');
                notaContainer.style.backgroundColor = bgColor;
                document.getElementById("colorRelleno").value = bgColor;

                // Extraer el contenido real sin el wrapper
                nota.innerHTML = wrapper.innerHTML;
            }
        }

        // Guardar la posición del cursor antes de cualquier operación
        function saveSelection() {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                lastSelection = selection.getRangeAt(0).cloneRange();
            }
        }

        // Restaurar la posición del cursor después de una operación
        function restoreSelection() {
            if (lastSelection) {
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(lastSelection);
            }
        }

        // ------------------------------ FUNCIÓN DE AUTOGUARDADO ------------------------------


        // Función para guardar contenido
        function saveContent() {
            // Obtener el color de fondo actual
            const bgColor = notaContainer.style.backgroundColor || document.getElementById("colorRelleno")
                .value;

            // Envolver el contenido en un div con el atributo data-bg-color
            const wrappedContent = `<div data-bg-color="${bgColor}">${nota.innerHTML}</div>`;

            // Actualizar el contenido del editor
            editorNota.innerHTML = wrappedContent;

            // Si hay un formulario, actualizar el campo oculto
            const hiddenField = document.getElementById('contenido_html');
            if (hiddenField) {
                hiddenField.value = wrappedContent;
            }

            // Si estamos en una vista de edición con ID de nota, enviar al servidor
            const notaId = document.querySelector('.note-panel p')?.textContent.split(':')[1].trim();
            if (notaId && notaId !== 'null') {
                const formData = new FormData();
                formData.append('id_nota', notaId);
                formData.append('contenido_html', wrappedContent);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

                fetch('/notas/guardar', {
                    method: 'POST',
                    body: formData
                }).catch(error => console.error('Error saving note:', error));
            }
            autoSave();
        }

        // ------------------------------ COLOR DE FONDO DE LA NOTA ------------------------------
        const colorInput = document.getElementById("colorRelleno");
        if (colorInput) {
            colorInput.addEventListener("input", () => {
                saveSelection(); // Guardar posición del cursor
                const selectedColor = colorInput.value;
                notaContainer.style.backgroundColor = selectedColor;
                autoSave();
                restoreSelection(); // Restaurar posición del cursor
            });
        }

        // ------------------------------ FORMATEO DE TEXTO (NEGRITA, CURSIVA, SUBRAYADO) ------------------------------
        const boldBtn = document.getElementById("boldBtn");
        const italicBtn = document.getElementById("italicBtn");
        const underlineBtn = document.getElementById("underlineBtn");

        function toggleFormat(command, button) {
            saveSelection(); // Guardar posición del cursor
            document.execCommand(command, false, null);
            button.classList.toggle("active");

            // Aplicar font-family a los nuevos elementos formateados
            const formattedTags = nota.querySelectorAll("b, strong, i, em, u");
            formattedTags.forEach((el) => {
                el.style.fontFamily = fuenteActiva;
            });

            // Asegurar que <font> tenga estilo real aplicado
            const fonts = nota.querySelectorAll("font[face]");
            fonts.forEach((el) => {
                el.style.fontFamily = el.getAttribute("face");
            });

            nota.focus();
            autoSave();
            restoreSelection(); // Restaurar posición del cursor
        }

        if (boldBtn) boldBtn.addEventListener("click", () => toggleFormat("bold", boldBtn));
        if (italicBtn) italicBtn.addEventListener("click", () => toggleFormat("italic", italicBtn));
        if (underlineBtn) underlineBtn.addEventListener("click", () => toggleFormat("underline", underlineBtn));

        // ------------------------------ FUENTE DEL TEXTO ------------------------------
        const fontSelect = document.querySelector(".font-family-select");
        if (fontSelect) {
            fontSelect.addEventListener("change", function() {
                saveSelection(); // Guardar posición del cursor
                const selectedFont = this.value;
                fuenteActiva = selectedFont;

                // Aplicar al texto seleccionado
                document.execCommand("fontName", false, fuenteActiva);

                // Aplicar font-family real
                const fonts = nota.querySelectorAll("font[face]");
                fonts.forEach((el) => {
                    el.style.fontFamily = el.getAttribute("face");
                });

                // Insertar span invisible con esa fuente
                const span = document.createElement("span");
                span.style.fontFamily = fuenteActiva;
                span.innerHTML = "&#8203;"; // Carácter de ancho cero

                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    range.insertNode(span);
                    range.setStartAfter(span);
                    range.setEndAfter(span);
                    selection.removeAllRanges();
                    selection.addRange(range);
                    lastSelection = range.cloneRange(); // Actualizar lastSelection
                }

                nota.focus();
                autoSave();
            });
        }

        // Mantener fuente activa al saltar de línea
        nota.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                setTimeout(() => {
                    saveSelection(); // Guardar posición del cursor
                    document.execCommand("fontName", false, fuenteActiva);

                    // Asegurar que todos los <font> tengan estilo real aplicado
                    const fonts = nota.querySelectorAll("font[face]");
                    fonts.forEach((el) => {
                        el.style.fontFamily = el.getAttribute("face");
                    });

                    autoSave();
                    restoreSelection(); // Restaurar posición del cursor
                }, 0);
            }
        });

        // ------------------------------ TAMAÑO DE FUENTE ------------------------------
        const fontSizeSelect = document.querySelector(".font-size-select");
        if (fontSizeSelect) {
            fontSizeSelect.addEventListener("change", function() {
                saveSelection(); // Guardar posición del cursor
                const selectedSize = this.value;
                tamanoActivo = selectedSize;

                // Aplicar al texto seleccionado
                document.execCommand("fontSize", false, "7"); // Usamos '7' como placeholder

                // Reemplazar los <font size="7"> con estilo real
                const fonts = nota.querySelectorAll('font[size="7"]');
                fonts.forEach((el) => {
                    el.removeAttribute("size");
                    el.style.fontSize = `${tamanoActivo}px`;
                });

                // Mantener tamaño tras escribir
                const span = document.createElement("span");
                span.style.fontSize = `${tamanoActivo}px`;
                span.innerHTML = "&#8203;"; // Carácter de ancho cero

                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    range.insertNode(span);
                    range.setStartAfter(span);
                    range.setEndAfter(span);
                    selection.removeAllRanges();
                    selection.addRange(range);
                    lastSelection = range.cloneRange(); // Actualizar lastSelection
                }

                nota.focus();
                autoSave();
            });
        }

        // ------------------------------ COLOR DE TEXTO ------------------------------
        const colorTexto = document.getElementById("colorTexto");
        if (colorTexto) {
            colorTexto.addEventListener("input", () => {
                saveSelection(); // Guardar posición del cursor
                const selectedColor = colorTexto.value;
                document.execCommand("foreColor", false, selectedColor);
                nota.focus();
                autoSave();
                restoreSelection(); // Restaurar posición del cursor
            });
        }

        // ------------------------------ RESALTADO DE TEXTO ------------------------------
        const highlightBtn = document.getElementById("highlightBtn");
        const colorMarcado = document.getElementById("colorMarcado");
        const clearHighlightBtn = document.getElementById("clearHighlightBtn");

        if (highlightBtn && colorMarcado) {
            colorMarcado.addEventListener("input", () => {
                saveSelection(); // Guardar posición del cursor
                const selectedColor = colorMarcado.value;
                document.execCommand("hiliteColor", false, selectedColor);
                nota.focus();
                autoSave();
                restoreSelection(); // Restaurar posición del cursor
            });

            highlightBtn.addEventListener("click", () => {
                saveSelection(); // Guardar posición del cursor
                const selectedColor = colorMarcado.value;
                document.execCommand("hiliteColor", false, selectedColor);
                nota.focus();
                autoSave();
                restoreSelection(); // Restaurar posición del cursor
            });
        }

        if (clearHighlightBtn) {
            clearHighlightBtn.addEventListener("click", () => {
                saveSelection(); // Guardar posición del cursor
                document.execCommand("hiliteColor", false, "transparent");
                nota.focus();
                autoSave();
                restoreSelection(); // Restaurar posición del cursor
            });
        }

        // ------------------------------ ALINEACIÓN DE TEXTO ------------------------------
        const alignLeft = document.getElementById("alignLeft");
        const alignCenter = document.getElementById("alignCenter");
        const alignRight = document.getElementById("alignRight");
        const alignJustify = document.getElementById("alignJustify");

        if (alignLeft)
            alignLeft.addEventListener("click", () => {
                saveSelection(); // Guardar posición del cursor
                document.execCommand("justifyLeft", false, null);
                nota.focus();
                autoSave();
                restoreSelection(); // Restaurar posición del cursor
            });

        if (alignCenter)
            alignCenter.addEventListener("click", () => {
                saveSelection(); // Guardar posición del cursor
                document.execCommand("justifyCenter", false, null);
                nota.focus();
                autoSave();
                restoreSelection(); // Restaurar posición del cursor
            });

        if (alignRight)
            alignRight.addEventListener("click", () => {
                saveSelection(); // Guardar posición del cursor
                document.execCommand("justifyRight", false, null);
                nota.focus();
                autoSave();
                restoreSelection(); // Restaurar posición del cursor
            });

        if (alignJustify)
            alignJustify.addEventListener("click", () => {
                saveSelection(); // Guardar posición del cursor
                document.execCommand("justifyFull", false, null);
                nota.focus();
                autoSave();
                restoreSelection(); // Restaurar posición del cursor
            });

        // ------------------------------ LISTAS ------------------------------
        // Obtener referencia al botón de lista con viñetas (puntos)
        const unorderedListBtn = document.getElementById("unorderedList");

        // Verificar que el botón existe y el elemento nota está definido
        if (unorderedListBtn && nota) {
            // Función para agregar una lista con viñetas (puntos)
            unorderedListBtn.addEventListener("click", function() {
                saveSelection(); // Guardar posición del cursor
                // Usar el comando nativo para insertar una lista no ordenada
                document.execCommand("insertUnorderedList", false, null);

                // Obtener la lista recién creada y aplicar el estilo de viñetas circulares
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    const listElement = getClosestList(range.commonAncestorContainer);

                    if (listElement) {
                        listElement.style.listStyleType = "disc"; // Usar viñetas circulares
                    }

                    lastSelection = range.cloneRange(); // Actualizar lastSelection
                }

                // Enfocar el área de la nota
                nota.focus();
                autoSave();
            });

            // Función auxiliar para encontrar el elemento de lista más cercano
            function getClosestList(element) {
                while (element && element !== nota) {
                    if (element.tagName === 'UL') {
                        return element;
                    }
                    element = element.parentNode;
                }
                return null;
            }

            // Función para mantener el estilo de lista al presionar Enter
            nota.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    setTimeout(() => {
                        saveSelection(); // Guardar posición del cursor
                        const selection = window.getSelection();
                        if (selection.rangeCount > 0) {
                            const range = selection.getRangeAt(0);
                            const listElement = getClosestList(range.commonAncestorContainer);

                            if (listElement) {
                                // Mantener el estilo de viñetas circulares
                                listElement.style.listStyleType = "disc";
                            }

                            lastSelection = range.cloneRange(); // Actualizar lastSelection
                        }
                        autoSave();
                    }, 0);
                }
            });
        }

        // Obtener referencia al botón de lista numerada
        const orderedListBtn = document.getElementById("orderedList");

        // Verificar que el botón existe y el elemento nota está definido
        if (orderedListBtn && nota) {
            // Función para agregar una lista numerada
            orderedListBtn.addEventListener("click", function() {
                saveSelection(); // Guardar posición del cursor
                // Usar el comando nativo para insertar una lista ordenada
                document.execCommand("insertOrderedList", false, null);

                // Obtener la lista recién creada y aplicar el estilo de numeración decimal
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    const listElement = getClosestOrderedList(range.commonAncestorContainer);

                    if (listElement) {
                        listElement.style.listStyleType = "decimal"; // Usar números (1, 2, 3...)
                    }

                    lastSelection = range.cloneRange(); // Actualizar lastSelection
                }

                // Enfocar el área de la nota
                nota.focus();
                autoSave();
            });

            // Función auxiliar para encontrar el elemento de lista ordenada más cercano
            function getClosestOrderedList(element) {
                while (element && element !== nota) {
                    if (element.tagName === 'OL') {
                        return element;
                    }
                    element = element.parentNode;
                }
                return null;
            }

            // Función para mantener el estilo de lista al presionar Enter
            nota.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    setTimeout(() => {
                        saveSelection(); // Guardar posición del cursor
                        const selection = window.getSelection();
                        if (selection.rangeCount > 0) {
                            const range = selection.getRangeAt(0);
                            const listElement = getClosestOrderedList(range
                                .commonAncestorContainer);

                            if (listElement) {
                                // Mantener el estilo de numeración decimal
                                listElement.style.listStyleType = "decimal";
                            }

                            lastSelection = range.cloneRange(); // Actualizar lastSelection
                        }
                        autoSave();
                    }, 0);
                }
            });
        }

        // ------------------------------ HERRAMIENTAS DE DIBUJO ------------------------------

        // Crear canvas para dibujo (no se recrea cada vez que se cambia de herramienta)
        function setupCanvas() {
            // Si no hay un canvas, lo creamos
            if (!canvas) {
                canvas = document.createElement("canvas");
                canvas.className = "drawing-canvas";
                canvas.width = notaContainer.offsetWidth;
                canvas.height = notaContainer.offsetHeight;
                canvas.style.position = "absolute";
                canvas.style.top = "0";
                canvas.style.left = "0";
                canvas.style.pointerEvents = "none"; // Para que no interfiera con el texto

                // Añadir canvas al contenedor de la nota
                notaContainer.style.position = "relative";
                notaContainer.appendChild(canvas);

                // Configurar contexto
                ctx = canvas.getContext("2d");
            }
        }

        // Cursor personalizado para dibujo
        function updateCursor() {
            const thicknessSlider = document.getElementById("thicknessSlider");
            const thickness = thicknessSlider ? Number.parseInt(thicknessSlider.value) : 5;

            if (!drawingTool) {
                nota.style.cursor = "text";
                if (canvas) canvas.style.pointerEvents = "none";
                return;
            }

            // Habilitar interacción con el canvas
            if (canvas) canvas.style.pointerEvents = "auto";

            // Crear cursor personalizado
            const cursorCanvas = document.createElement("canvas");
            const cursorCtx = cursorCanvas.getContext("2d");
            const cursorSize = thickness + 4; // Tamaño del cursor

            cursorCanvas.width = cursorSize;
            cursorCanvas.height = cursorSize;

            // Dibujar círculo para el cursor
            cursorCtx.beginPath();
            cursorCtx.arc(cursorSize / 2, cursorSize / 2, thickness / 2, 0, Math.PI * 2);

            if (drawingTool === "eraser") {
                cursorCtx.strokeStyle = "#000";
                cursorCtx.stroke();
            } else {
                const colorInput =
                    drawingTool === "pencil" ? document.getElementById("colorLapiz") : document.getElementById(
                        "colorPlumon");

                const color = colorInput ? colorInput.value : "#000";
                cursorCtx.fillStyle = color;
                cursorCtx.fill();
            }

            // Convertir a data URL
            const cursorDataURL = cursorCanvas.toDataURL();

            // Aplicar cursor personalizado
            nota.style.cursor = `url(${cursorDataURL}) ${cursorSize / 2} ${cursorSize / 2}, auto`;
        }

        // Funciones de dibujo
        function startDrawing(e) {
            if (!drawingTool || !ctx) return;

            isDrawing = true;

            // Obtener posición relativa al canvas
            const rect = canvas.getBoundingClientRect();
            lastX = e.clientX - rect.left;
            lastY = e.clientY - rect.top;
        }

        function draw(e) {
            if (!isDrawing || !drawingTool || !ctx) return;

            // Obtener posición actual
            const rect = canvas.getBoundingClientRect();
            const currentX = e.clientX - rect.left;
            const currentY = e.clientY - rect.top;

            // Configurar estilo según herramienta
            const thicknessSlider = document.getElementById("thicknessSlider");
            const thickness = thicknessSlider ? Number.parseInt(thicknessSlider.value) : 5;

            ctx.lineWidth = thickness;
            ctx.lineCap = "round";
            ctx.lineJoin = "round";

            if (drawingTool === "eraser") {
                // Corregido: Usar globalCompositeOperation para borrar
                ctx.globalCompositeOperation = "destination-out";
                ctx.strokeStyle = "rgba(0,0,0,1)"; // El color no importa cuando se usa destination-out
            } else {
                ctx.globalCompositeOperation = "source-over";

                const colorInput =
                    drawingTool === "pencil" ? document.getElementById("colorLapiz") : document.getElementById(
                        "colorPlumon");

                const color = colorInput ? colorInput.value : "#000";
                ctx.strokeStyle = color;

                // Si es plumón, añadir transparencia
                if (drawingTool === "marker") {
                    ctx.globalAlpha = 0.5;
                } else {
                    ctx.globalAlpha = 1.0;
                }
            }

            // Dibujar línea
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(currentX, currentY);
            ctx.stroke();

            // Actualizar última posición
            lastX = currentX;
            lastY = currentY;
        }

        function stopDrawing() {
            isDrawing = false;

            // Guardar el canvas como imagen y añadirlo al contenido
            if (drawingTool && canvas) {
                const drawingData = canvas.toDataURL();

                // Crear una imagen con el dibujo
                const img = new Image();
                img.src = drawingData;
                img.style.position = "absolute";
                img.style.top = "0";
                img.style.left = "0";
                img.style.width = "100%";
                img.style.height = "100%";
                img.style.pointerEvents = "none";
                img.style.zIndex = "1";

                // Añadir la imagen al contenedor
                notaContainer.appendChild(img);

                // Limpiar el canvas para el próximo dibujo
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Guardar el contenido actualizado
                autoSave();
            }
        }

        // Botones de herramientas de dibujo
        const pencilBtn = document.getElementById("pencilBtn");
        const markerBtn = document.getElementById("markerBtn");
        const eraserBtn = document.getElementById("eraserBtn");
        const thicknessSlider = document.getElementById("thicknessSlider");

        // Configurar botones de dibujo
        if (pencilBtn) {
            pencilBtn.addEventListener("click", () => {
                if (drawingTool === "pencil") {
                    // Desactivar
                    drawingTool = null;
                    pencilBtn.classList.remove("active");
                    if (canvas) canvas.style.pointerEvents = "none";
                } else {
                    // Activar lápiz
                    drawingTool = "pencil";
                    setupCanvas();

                    // Desactivar otras herramientas
                    if (markerBtn) markerBtn.classList.remove("active");
                    if (eraserBtn) eraserBtn.classList.remove("active");

                    // Activar este botón
                    pencilBtn.classList.add("active");
                }

                updateCursor();
            });
        }

        if (markerBtn) {
            markerBtn.addEventListener("click", () => {
                if (drawingTool === "marker") {
                    // Desactivar
                    drawingTool = null;
                    markerBtn.classList.remove("active");
                    if (canvas) canvas.style.pointerEvents = "none";
                } else {
                    // Activar plumón
                    drawingTool = "marker";
                    setupCanvas();

                    // Desactivar otras herramientas
                    if (pencilBtn) pencilBtn.classList.remove("active");
                    if (eraserBtn) eraserBtn.classList.remove("active");

                    // Activar este botón
                    markerBtn.classList.add("active");
                }

                updateCursor();
            });
        }

        if (eraserBtn) {
            eraserBtn.addEventListener("click", () => {
                if (drawingTool === "eraser") {
                    // Desactivar
                    drawingTool = null;
                    eraserBtn.classList.remove("active");
                    if (canvas) canvas.style.pointerEvents = "none";
                } else {
                    // Activar borrador
                    drawingTool = "eraser";
                    setupCanvas();

                    // Desactivar otras herramientas
                    if (pencilBtn) pencilBtn.classList.remove("active");
                    if (markerBtn) markerBtn.classList.remove("active");

                    // Activar este botón
                    eraserBtn.classList.add("active");
                }

                updateCursor();
            });
        }

        // Actualizar cursor al cambiar grosor
        if (thicknessSlider) {
            thicknessSlider.addEventListener("input", updateCursor);
        }

        // Eventos de dibujo
        notaContainer.addEventListener("mousedown", startDrawing);
        notaContainer.addEventListener("mousemove", draw);
        notaContainer.addEventListener("mouseup", stopDrawing);
        notaContainer.addEventListener("mouseout", stopDrawing);

        // ------------------------------ MANEJO DE IMÁGENES ------------------------------
        const addImageBtn = document.getElementById("addImageBtn");
        const imageInput = document.getElementById("imageInput");
        const imageSizeSelect = document.getElementById("imageSizeSelect");
        const deleteImageBtn = document.getElementById("deleteImageBtn");

        // Añadir imagen
        if (addImageBtn && imageInput) {
            addImageBtn.addEventListener("click", () => {
                // Guardar la posición actual del cursor
                saveSelection();

                // Activar el input de archivo
                imageInput.click();

                // Cuando se selecciona un archivo
                imageInput.onchange = function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const reader = new FileReader();

                        reader.onload = (e) => {
                            // Crear elemento de imagen
                            const img = document.createElement("img");
                            img.src = e.target.result;
                            img.className = "nota-image";
                            img.style.maxWidth = "100%";
                            img.style.height = "auto";
                            img.style.cursor = "pointer";
                            img.setAttribute("data-selectable",
                                "true"); // Marcar como seleccionable

                            // Añadir evento de clic para seleccionar la imagen
                            img.addEventListener("click", (e) => {
                                e.preventDefault();
                                e.stopPropagation(); // Evitar que el clic se propague

                                // Deseleccionar imagen anterior
                                if (selectedImage) {
                                    selectedImage.style.outline = "none";
                                }

                                // Seleccionar esta imagen
                                selectedImage = img;
                                selectedImage.style.outline = "2px solid #007bff";

                                // Mostrar controles de imagen
                                if (imageSizeSelect) imageSizeSelect.disabled = false;
                                if (deleteImageBtn) deleteImageBtn.disabled = false;
                            });

                            // Insertar imagen en la posición del cursor
                            if (lastSelection) {
                                const selection = window.getSelection();
                                selection.removeAllRanges();
                                selection.addRange(lastSelection);
                                const range = selection.getRangeAt(0);
                                range.insertNode(img);

                                // Mover el cursor después de la imagen
                                range.setStartAfter(img);
                                range.setEndAfter(img);
                                selection.removeAllRanges();
                                selection.addRange(range);
                                lastSelection = range.cloneRange(); // Actualizar lastSelection
                            } else {
                                nota.appendChild(img);
                            }

                            // Guardar contenido
                            autoSave();
                        };

                        reader.readAsDataURL(file);
                    }
                };
            });
        }

        // Cambiar tamaño de imagen
        if (imageSizeSelect) {
            imageSizeSelect.addEventListener("change", function() {
                if (!selectedImage) return;

                const size = this.value;

                switch (size) {
                    case "xsmall":
                        selectedImage.style.width = "15%";
                        break;
                    case "small":
                        selectedImage.style.width = "25%";
                        break;
                    case "medium":
                        selectedImage.style.width = "50%";
                        break;
                    case "large":
                        selectedImage.style.width = "75%";
                        break;
                    case "original":
                        selectedImage.style.width = "auto";
                        break;
                }

                // Guardar contenido
                autoSave();

                // Resetear el select
                this.selectedIndex = 0;
            });

            // Desactivar por defecto
            imageSizeSelect.disabled = true;
        }

        // Eliminar imagen
        if (deleteImageBtn) {
            deleteImageBtn.addEventListener("click", () => {
                if (!selectedImage) return;

                // Eliminar imagen
                selectedImage.remove();
                selectedImage = null;

                // Desactivar controles
                if (imageSizeSelect) imageSizeSelect.disabled = true;
                deleteImageBtn.disabled = true;

                // Guardar contenido
                autoSave();
            });

            // Desactivar por defecto
            deleteImageBtn.disabled = true;
        }

        // Mejorar la selección de imágenes - Asegurarse que las imágenes sean seleccionables
        function makeImagesSelectable() {
            const images = nota.querySelectorAll('img');
            images.forEach(img => {
                if (!img.hasAttribute('data-selectable')) {
                    img.setAttribute('data-selectable', 'true');
                    img.style.cursor = 'pointer';

                    img.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation(); // Evitar que el clic se propague

                        // Deseleccionar imagen anterior
                        if (selectedImage) {
                            selectedImage.style.outline = "none";
                        }

                        // Seleccionar esta imagen
                        selectedImage = img;
                        selectedImage.style.outline = "2px solid #007bff";

                        // Mostrar controles de imagen
                        if (imageSizeSelect) imageSizeSelect.disabled = false;
                        if (deleteImageBtn) deleteImageBtn.disabled = false;
                    });
                }
            });
        }

        // Hacer imágenes seleccionables al cargar
        makeImagesSelectable();

        // Hacer imágenes seleccionables después de cada cambio
        nota.addEventListener('input', () => {
            setTimeout(makeImagesSelectable, 100);
        });

        // Deseleccionar imagen al hacer clic en otra parte
        nota.addEventListener("click", (e) => {
            // Solo deseleccionar si no se hizo clic en una imagen
            if (e.target.tagName !== 'IMG' && selectedImage) {
                selectedImage.style.outline = "none";
                selectedImage = null;

                // Desactivar controles
                if (imageSizeSelect) imageSizeSelect.disabled = true;
                if (deleteImageBtn) deleteImageBtn.disabled = true;
            }
        });

        // ------------------------------ RECONOCIMIENTO DE VOZ ------------------------------
        const voiceBtn = document.getElementById("voiceBtn");
        const playBtn = document.querySelector(".btn.icon-btn[title='Play']");
        const pauseBtn = document.querySelector(".btn.icon-btn[title='Pause']");
        const stopBtn = document.querySelector(".btn.icon-btn[title='Stop']");

        // Variables para el reconocimiento de voz
        let recognition = null;
        let isRecording = false;

        // Variables para la síntesis de voz
        let utterance = null;
        let isSpeaking = false;

        // ------------------------------ RECONOCIMIENTO DE VOZ ------------------------------
        if (voiceBtn && ("webkitSpeechRecognition" in window || "SpeechRecognition" in window)) {
            // Inicializar el reconocimiento de voz
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.continuous = true;
            recognition.interimResults = true;
            recognition.lang = "es-ES"; // Idioma español

            // Evento de clic en el botón de voz
            voiceBtn.addEventListener("click", () => {
                if (isRecording) {
                    // Detener grabación
                    recognition.stop();
                    voiceBtn.classList.remove("active");
                    voiceBtn.innerHTML = '<i class="fas fa-microphone"></i> Voice';
                    isRecording = false;
                } else {
                    // Iniciar grabación
                    try {
                        // Guardar la posición actual del cursor antes de iniciar
                        saveSelection();
                        recognition.start();
                        voiceBtn.classList.add("active");
                        voiceBtn.innerHTML = '<i class="fas fa-microphone-slash"></i> Stop';
                        isRecording = true;
                    } catch (error) {
                        console.error("Error al iniciar el reconocimiento de voz:", error);
                        alert("No se pudo iniciar el reconocimiento de voz. Intente de nuevo.");
                    }
                }
            });

            // Evento de resultado del reconocimiento
            recognition.onresult = (event) => {
                // Obtener el último resultado
                const lastResultIndex = event.results.length - 1;
                const transcript = event.results[lastResultIndex][0].transcript;

                // Restaurar la posición del cursor guardada
                restoreSelection();

                // Insertar texto en la posición del cursor
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);

                    // Si es un resultado final, insertar el texto
                    if (event.results[lastResultIndex].isFinal) {
                        // Crear un nodo de texto con el resultado
                        const textNode = document.createTextNode(transcript + " ");

                        // Insertar el texto en la posición actual
                        range.deleteContents();
                        range.insertNode(textNode);

                        // Mover el cursor al final del texto insertado
                        range.setStartAfter(textNode);
                        range.setEndAfter(textNode);
                        selection.removeAllRanges();
                        selection.addRange(range);

                        // Actualizar la selección guardada
                        lastSelection = range.cloneRange();
                    }
                } else {
                    // Si no hay selección, añadir al final
                    const textNode = document.createTextNode(transcript + " ");
                    nota.appendChild(textNode);

                    // Mover el cursor al final
                    const range = document.createRange();
                    range.selectNodeContents(nota);
                    range.collapse(false);
                    selection.removeAllRanges();
                    selection.addRange(range);

                    // Actualizar la selección guardada
                    lastSelection = range.cloneRange();
                }

                // Guardar contenido
                autoSave();
            };

            // Eventos de error y fin
            recognition.onerror = (event) => {
                console.error("Error en reconocimiento de voz:", event.error);
                voiceBtn.classList.remove("active");
                voiceBtn.innerHTML = '<i class="fas fa-microphone"></i> Voice';
                isRecording = false;
            };

            recognition.onend = () => {
                // Si todavía estamos en modo de grabación pero el reconocimiento terminó,
                // reiniciarlo (esto puede ocurrir por tiempos de espera)
                if (isRecording) {
                    try {
                        recognition.start();
                    } catch (error) {
                        console.error("Error al reiniciar el reconocimiento de voz:", error);
                        voiceBtn.classList.remove("active");
                        voiceBtn.innerHTML = '<i class="fas fa-microphone"></i> Voice';
                        isRecording = false;
                    }
                }
            };
        } else {
            // Si el navegador no soporta reconocimiento de voz
            if (voiceBtn) {
                voiceBtn.disabled = true;
                voiceBtn.title = "Su navegador no soporta reconocimiento de voz";
            }
        }

        // ------------------------------ SÍNTESIS DE VOZ (TEXT-TO-SPEECH) ------------------------------
        if ("speechSynthesis" in window) {
            // Función para obtener el texto a leer
            function getTextToSpeak() {
                const selection = window.getSelection();
                let text = "";

                if (selection.toString().trim() !== "") {
                    // Si hay texto seleccionado, usar ese
                    text = selection.toString();
                } else {
                    // Si no hay selección, usar todo el texto de la nota
                    text = nota.textContent;
                }

                return text.trim();
            }

            // Botón de reproducción (Play)
            if (playBtn) {
                playBtn.addEventListener("click", () => {
                    // Si ya estamos hablando y pausados, reanudar
                    if (isSpeaking && speechSynthesis.paused) {
                        speechSynthesis.resume();
                        return;
                    }

                    // Si ya estamos hablando, detener la reproducción actual
                    if (isSpeaking) {
                        speechSynthesis.cancel();
                    }

                    // Obtener el texto a leer
                    const text = getTextToSpeak();
                    if (text === "") return;

                    // Crear nueva instancia de síntesis de voz
                    utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = "es-ES"; // Idioma español

                    // Evento para cuando termine de hablar
                    utterance.onend = () => {
                        isSpeaking = false;
                        playBtn.classList.remove("active");
                    };

                    // Evento para errores
                    utterance.onerror = (event) => {
                        console.error("Error en síntesis de voz:", event.error);
                        isSpeaking = false;
                        playBtn.classList.remove("active");
                    };

                    // Reproducir
                    speechSynthesis.speak(utterance);
                    isSpeaking = true;
                    playBtn.classList.add("active");
                });
            }

            // Botón de pausa
            if (pauseBtn) {
                pauseBtn.addEventListener("click", () => {
                    if (isSpeaking && !speechSynthesis.paused) {
                        speechSynthesis.pause();
                        pauseBtn.classList.add("active");
                    } else if (isSpeaking && speechSynthesis.paused) {
                        speechSynthesis.resume();
                        pauseBtn.classList.remove("active");
                    }
                });
            }

            // Botón de detener
            if (stopBtn) {
                stopBtn.addEventListener("click", () => {
                    if (isSpeaking) {
                        speechSynthesis.cancel();
                        isSpeaking = false;
                        playBtn.classList.remove("active");
                        pauseBtn.classList.remove("active");
                    }
                });
            }
        } else {
            // Si el navegador no soporta síntesis de voz
            if (playBtn) playBtn.disabled = true;
            if (pauseBtn) pauseBtn.disabled = true;
            if (stopBtn) stopBtn.disabled = true;

            if (playBtn) playBtn.title = "Su navegador no soporta síntesis de voz";
        }

        // Asegurarse de que el formulario guarde el contenido completo al enviar
        const noteForm = document.getElementById('noteForm');
        if (noteForm) {
            noteForm.addEventListener('submit', function() {
                // Obtener el color de fondo actual
                const bgColor = notaContainer.style.backgroundColor || document.getElementById(
                    "colorRelleno").value;

                // Envolver el contenido en un div con el atributo data-bg-color
                const wrappedContent = `<div data-bg-color="${bgColor}">${nota.innerHTML}</div>`;

                // Actualizar el campo oculto
                const hiddenField = document.getElementById('contenido_html');
                if (hiddenField) {
                    hiddenField.value = wrappedContent;
                }
            });
        }

        // Guardar la posición del cursor en cada interacción con el editor
        nota.addEventListener('mouseup', saveSelection);
        nota.addEventListener('keyup', saveSelection);

        // Prevenir que el cursor salte al inicio al hacer cambios
        nota.addEventListener('input', (e) => {
            // Solo guardar la selección si no estamos en medio de una operación de autoguardado
            if (!lastTimeout) {
                saveSelection();
            }

            // Guardar en sessionStorage después de cada cambio
            autoSave();
        });

        // Guardar antes de cerrar la ventana o navegar a otra página
        window.addEventListener('beforeunload', saveToSessionStorage);
    });
    </script>
</body>

</html>