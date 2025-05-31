 // --------------------------------------------- ALTA DE USUARIO -------------------------------------------------
        // Get the modal
        var modal = document.getElementById("registrationModal");

        // Get the button that opens the modal
        var btn = document.getElementById("openModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Limpiar mensajes de error previos
            document.querySelectorAll('.error').forEach(function(el) {
                el.textContent = '';
            });
            document.getElementById('generalError').textContent = '';

            // Enviar datos del formulario mediante fetch
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        if (data.errors) {
                            // Mostrar errores específicos
                            for (let field in data.errors) {
                                let errorElement = document.getElementById(field + '-error');
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                }
                            }
                        } else if (data.message) {
                            // Mostrar error general
                            document.getElementById('generalError').textContent = data.message;
                        }
                        throw new Error('Errores de validación detectados.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = "{{ route('crud') }}";
                }
            })
            .catch(error => {
                console.error('Error:', error.message);
                if (!document.getElementById('generalError').textContent) {
                    //document.getElementById('generalError').textContent = 'Ocurrió un error al procesar la solicitud. Por favor, inténtalo de nuevo.';
                }
            });
        });

        /* ----------------------------- Alta de usuario - Boton cancelar - tachita X --------------------------------------------- */
        // Obtener los elementos del modal, del formulario y los mensajes de error
        var modal = document.getElementById("registrationModal");
        var closeButton = document.getElementsByClassName("close")[0];
        var form = document.getElementById("registrationForm");

        // Limpiar todos los mensajes de error
        function clearErrors() {
            var errorElements = document.querySelectorAll('.error');
            errorElements.forEach(function(element) {
                element.innerHTML = '';  // Limpiar el texto de los mensajes de error
            });
        }

        // Función para cerrar el modal al hacer clic en la tachita
        closeButton.onclick = function() {
            modal.style.display = "none";  // Cerrar el modal
            form.reset();  // Limpiar el formulario
            clearErrors();  // Limpiar los mensajes de error
        };

        // Función para abrir el modal (cuando sea necesario)
        function openModal() {
            modal.style.display = "block";
        }

        function closeModal() {
            document.getElementById('updateUserModal').style.display = 'none';
        }

        /* ---------------------------------------- Actualizar Informacion del Usuario ------------------------------------------ */
   
          // Función para abrir el modal con los datos del usuario
          function showUpdateUserModal(user) {
            // Llena los campos del formulario con los datos del usuario
            document.getElementById('edit-id_usuario').value = user.id_usuario;
            document.getElementById('edit-nombre').value = user.nombre;
            document.getElementById('edit-apellido').value = user.apellido;
            document.getElementById('edit-nombre_usuario').value = user.nombre_usuario;
            document.getElementById('edit-correo').value = user.correo_electronico;
            document.getElementById('edit-fecha_nacimiento').value = user.fecha_nacimiento;
            document.getElementById('edit-genero').value = user.genero;
            document.getElementById('edit-telefono').value = user.telefono;
            document.getElementById('edit-rol').value = user.rol;

            // Contraseñas no se llenan automáticamente por seguridad
            document.getElementById('edit-contraseña').value = '';
            document.getElementById('edit-contraseña_confirmation').value = '';

            

            // Muestra el modal
            document.getElementById('updateUserModal').style.display = 'block';

            
        }

        function closeModalAndClearErrors() {
            // Oculta el modal
            document.getElementById('updateUserModal').style.display = 'none';

            // Elimina la clase `open` si existe
            document.getElementById('updateUserModal').classList.remove('open');

            // Limpia errores de validación si lo deseas
            const errors = document.querySelectorAll('#updateUserModal .error');
            errors.forEach(e => e.remove());

            // Limpia los campos si quieres:
            document.getElementById('updateUserForm').reset();
        }


   

        // -------------------------------- Función para cerrar el modal con la tachita -----------------------------------

        function toggleReadonly(fieldId, type = 'input') {
            const field = document.getElementById(fieldId);
            if (type === 'input') {
                field.readOnly = !field.readOnly;
            } else if (type === 'select') {
                field.disabled = !field.disabled;
            }
        }
        
        // ----------------------------------- LIMPIAR BUSQEUDA --------------------------------------------------
        function resetSearch() {
            // Limpia el campo de búsqueda
            document.getElementById('search-input').value = '';
    
            // Envía el formulario para mostrar todos los resultados
            const form = document.querySelector('.search-bar form');
            form.submit();
        }

        // ------------------------------- Visibilidad de la contraseña --------------------------------
        // Agregar evento a cada ícono para alternar la visibilidad de las contraseñas
        document.getElementById('togglePassword').addEventListener('click', function () {
            togglePasswordVisibility('contraseña', 'togglePassword');
        });

        document.getElementById('togglePasswordConfirmation').addEventListener('click', function () {
            togglePasswordVisibility('contraseña_confirmation', 'togglePasswordConfirmation');
        });

        // Función genérica para alternar visibilidad de contraseña
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            // Cambiar tipo de input entre 'password' y 'text'
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // Alternar entre las clases de los íconos
            if (type === 'text') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }


        // Alternar la visibilidad de la contraseña
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            // Cambiar tipo de input entre 'password' y 'text'
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // Alternar entre las clases de los íconos
            if (type === 'text') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        // Alternar entre modo de edición y solo lectura
        function toggleReadonly(inputId) {
            const inputField = document.getElementById(inputId);

            // Cambiar atributo readonly
            inputField.readOnly = !inputField.readOnly;

            // Agregar o quitar clase de edición activa si es necesario
            if (!inputField.readOnly) {
                inputField.classList.add('editing'); // Opcional: Agregar estilo visual para indicar que está editable
            } else {
                inputField.classList.remove('editing');
            }
        }

        

