const extraFields = document.getElementById('patriaExtraFields');

/*FUNCIONES  */

//validar inputs

function validarInputVacio1(input, telefono=false) {
    // Verificar si el input es un telefono
    if(typeof(input.value) === 'integer' && telefono){
        input.value = input.value.replace(/\D/g, '').slice(0, 11)
    }

    if(typeof(input.value) === 'integer' && !telefono){
        input.value = input.value.replace(/\D/g, '').slice(0, 8)
    }


}


//Mostrar fecha en tiempo real
function mostrarFecha(IdfechaInput, IdedadInputs) {
    let fechaInput = document.getElementById(IdfechaInput);
    let edadInput = document.getElementById(IdedadInputs);
        if (fechaInput && edadInput) {
            fechaInput.addEventListener('input', function() {
                const fecha = this.value;
                if (fecha) {
                    const hoy = new Date();
                    const nacimiento = new Date(fecha);
                    let edad = hoy.getFullYear() - nacimiento.getFullYear();
                    const m = hoy.getMonth() - nacimiento.getMonth();
                    if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
                        edad--;
                    }
                    edadInput.value = edad >= 0 ? edad : '';
                } else {
                    edadInput.value = '';
                }
            });
        }
}

// Función para validar campos requeridos

function validarInputVacio(input) {
    if (!input) return false;
    if (input.value.trim() === '') {
        input.classList.add('is-invalid');
        return false;
    } else {
        input.classList.remove('is-invalid');
        return true;
    }
}


//VALIDACIONES DE INPUTS
document.addEventListener('DOMContentLoaded', function() {
    // Validación para el campo Cédula en editar estudiante
    document.getElementById('editCedula').addEventListener('input', function() {

        // Solo números y máximo 8 dígitos
        
        validarInputVacio1(this);
    });

    // Validación para el campo ¿Cuántas veces a la semana? en editar estudiante
    var vecesSemana = document.getElementById('editVecesSemana');
        if (vecesSemana) {
            vecesSemana.addEventListener('input', function() {
                // Solo permitir un dígito entre 1 y 7
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1);
                this.classList.remove('is-invalid');
            });
        }
    // Mostrar la edad automáticamente al cambiar la fecha de nacimiento en el modal de editar
    mostrarFecha('editFechaNacimiento', 'editEdad');

})






//*Script para el inputt de buscar
document.addEventListener('DOMContentLoaded', function() {
    //Inputs en variables
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');

    searchButton.addEventListener('click', function() {

        // Elimina cualquier mensaje de error existente
        const feedback = searchInput.parentNode.querySelector('.invalid-feedback');
        if (feedback) feedback.remove();

        if (searchInput.value.trim() === '') {
            searchInput.classList.add('is-invalid');
            const newFeedback = document.createElement('div');
            newFeedback.className = 'invalid-feedback';
            newFeedback.textContent = 'El campo de búsqueda no puede estar vacío.';
            searchInput.parentNode.appendChild(newFeedback);
            searchInput.focus();
        } else {
            searchInput.classList.remove('is-invalid');
            // Aquí puedes agregar la lógica de búsqueda
            const searchValue = searchInput.value.trim().toLowerCase();
            window.location.href = `/dashboard/estudiantes?search=${encodeURIComponent(searchValue)}`;
        }
    });

    //Hacer que el botón de búsqueda se active al presionar Enter
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('searchButton').click();
        }
    });

    searchInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        const feedback = this.parentNode.querySelector('.invalid-feedback');
        if (feedback) feedback.remove();
    });

    searchInput.addEventListener('blur', function() {
        // Elimina cualquier mensaje de error existente
        const feedback = this.parentNode.querySelector('.invalid-feedback');
        if (feedback) feedback.remove();

        if (this.value.trim() === '') {
            this.classList.add('is-invalid');
            const newFeedback = document.createElement('div');
            newFeedback.className = 'invalid-feedback';
            newFeedback.textContent = 'El campo de búsqueda no puede estar vacío.';
            this.parentNode.appendChild(newFeedback);
        }
    });
});
    

/* Script para la edición de estudiantes */
document.addEventListener('DOMContentLoaded', function () {


    // Validación personalizada
    //Formulario de validacion de edicion
    document.getElementById('editStudentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let form = this;
        let valid = true;

        // Validar cédula
        const cedula = form.editCedula.value.trim();
        if (!/^\d{8}$/.test(cedula)) {
            form.editCedula.classList.add('is-invalid');
            valid = false;
        } else {
            form.editCedula.classList.remove('is-invalid');
        }

        // Validar nombre
        if (form.editNombre.value.trim() === '') {
            form.editNombre.classList.add('is-invalid');
            valid = false;
        } else {
            form.editNombre.classList.remove('is-invalid');
        }

        // Validar apellido
        if (form.editApellido.value.trim() === '') {
            form.editApellido.classList.add('is-invalid');
            valid = false;
        } else {
            form.editApellido.classList.remove('is-invalid');
        }

        // Validar sexo
        if (form.editSexo.value.trim() === '') {
            form.editSexo.classList.add('is-invalid');
            valid = false;
        } else {
            form.editSexo.classList.remove('is-invalid');
        }

        // Validar teléfono
        if (!/^\d{11}$/.test(form.editTelefono.value.trim())) {
            form.editTelefono.classList.add('is-invalid');
            valid = false;
        } else {
            form.editTelefono.classList.remove('is-invalid');
        }

        // Validar fecha de nacimiento
        if (form.editFechaNacimiento.value.trim() === '') {
            form.editFechaNacimiento.classList.add('is-invalid');
            valid = false;
        } else {
            form.editFechaNacimiento.classList.remove('is-invalid');
        }

        // Validar condición
        if (form.editCondicion.value.trim() === '') {
            form.editCondicion.classList.add('is-invalid');
            valid = false;
        } else {
            form.editCondicion.classList.remove('is-invalid');
        }

        // Validar email
        const email = form.editEmail.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '' || !emailRegex.test(email)) {
            form.editEmail.classList.add('is-invalid');
            valid = false;
        } else {
            form.editEmail.classList.remove('is-invalid');
        }

        // Validar dirección
        if (form.editDireccion.value.trim() === '') {
            form.editDireccion.classList.add('is-invalid');
            valid = false;
        } else {
            form.editDireccion.classList.remove('is-invalid');
        }

        // Validar procedencia
        if (form.editProcedencia.value.trim() === '') {
            form.editProcedencia.classList.add('is-invalid');
            valid = false;
        } else {
            form.editProcedencia.classList.remove('is-invalid');
        }

        // Los campos "¿Es Foráneo?" y "¿Está registrado en patria?" NO son obligatorios, así que no se validan aquí.

        // Validar campos extra si es foráneo
        if (form.editForaneo.checked) {
            // Dirección temporal
            if (form.editDireccionTemporal.value.trim() === '') {
                form.editDireccionTemporal.classList.add('is-invalid');
                valid = false;
            } else {
                form.editDireccionTemporal.classList.remove('is-invalid');
            }

            // ¿Pagas residencia?
            const pagaResidenciaSi = form.editPagaResidenciaSi;
            const pagaResidenciaNo = form.editPagaResidenciaNo;
            const pagaResidenciaGroup = pagaResidenciaSi.closest('.mb-3');
            const pagaResidenciaFeedback = pagaResidenciaGroup.querySelector('.invalid-feedback');
            if (!pagaResidenciaSi.checked && !pagaResidenciaNo.checked) {
                pagaResidenciaFeedback.style.display = 'block';
                valid = false;
            } else {
                pagaResidenciaFeedback.style.display = 'none';
            }
            // ¿Cuánto pagas? (si seleccionó sí)
            if (pagaResidenciaSi.checked) {
                if (form.editCuantoPagasResidencia.value.trim() === '') {
                    form.editCuantoPagasResidencia.classList.add('is-invalid');
                    valid = false;
                } else {
                    form.editCuantoPagasResidencia.classList.remove('is-invalid');
                }
            }

            // ¿Viajas a diario?
            const viajaDiarioSi = form.editViajaDiarioSi;
            const viajaDiarioNo = form.editViajaDiarioNo;
            const viajaDiarioGroup = viajaDiarioSi.closest('.mb-3');
            const viajaDiarioFeedback = viajaDiarioGroup.querySelector('.invalid-feedback');
            if (!viajaDiarioSi.checked && !viajaDiarioNo.checked) {
                viajaDiarioFeedback.style.display = 'block';
                valid = false;
            } else {
                viajaDiarioFeedback.style.display = 'none';
            }
            // ¿Cuántas veces a la semana? (si seleccionó sí)
            if (viajaDiarioSi.checked) {
                if (form.editVecesSemana.value.trim() === '') {
                    form.editVecesSemana.classList.add('is-invalid');
                    valid = false;
                } else {
                    form.editVecesSemana.classList.remove('is-invalid');
                }
            }

            // Tiempo de viaje (al menos uno)
            if (form.editTiempoViajeHoras.value.trim() === '' && form.editTiempoViajeMinutos.value.trim() === '') {
                form.editTiempoViajeHoras.classList.add('is-invalid');
                form.editTiempoViajeMinutos.classList.add('is-invalid');
                valid = false;
            } else {
                form.editTiempoViajeHoras.classList.remove('is-invalid');
                form.editTiempoViajeMinutos.classList.remove('is-invalid');
            }

            // Gasto diario en pasaje
            if (form.editGastoDiarioPasaje.value.trim() === '') {
                form.editGastoDiarioPasaje.classList.add('is-invalid');
                valid = false;
            } else {
                form.editGastoDiarioPasaje.classList.remove('is-invalid');
            }
        }

        if (!valid) return;

        //Mensaje de confirmación
        Swal.fire({
            title: '¿Estas seguro que quieres actualizar los datos?',
            text: 'Comprueba los datos antes de confirmar.',
            icon: 'warning',
            confirmButtonText: 'Aceptar',
            showCancelButton: true,
        })
        .then((result)=>{
            if (result.isConfirmed) {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: 'Los datos han sido actualizados',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                })
                
                // Cierra el modal después de actualizar
                var modal = bootstrap.Modal.getInstance(document.getElementById('staticBackdrop'));
                modal.hide();
            }
        });

    });

    // Quitar la clase is-invalid al escribir
    ['editCedula', 'editNombre', 'editApellido'].forEach(function(id) {
        document.getElementById(id).addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });


});


// Mostrar/ocultar campos extra de Portuguesa en editar estudiante
document.addEventListener('DOMContentLoaded', function() {
    const direccionSelect = document.getElementById('editDireccion');
    const portuguesaFields = document.getElementById('portuguesaFields');
    direccionSelect.addEventListener('change', function() {
        if (this.value === 'Portuguesa') {
            portuguesaFields.style.display = '';
        } else {
            portuguesaFields.style.display = 'none';
            // Opcional: limpiar los campos si se ocultan
            document.getElementById('editSector').value = '';
            document.getElementById('editCalle').value = '';
            document.getElementById('editCasa').value = '';
        }
    });
});


// Mostrar/ocultar campos extra de Portuguesa en registrar estudiante
document.addEventListener('DOMContentLoaded', function() {
    const direccionSelect = document.getElementById('regDireccion');
    const portuguesaFields = document.getElementById('regPortuguesaFields');
        direccionSelect.addEventListener('change', function() {
        if (this.value === 'Portuguesa') {
            portuguesaFields.style.display = '';
        } else {
            portuguesaFields.style.display = 'none';
            document.getElementById('regSector').value = '';
            document.getElementById('regCalle').value = '';
            document.getElementById('regCasa').value = '';
        }
    });
});




// Mostrar/ocultar campos extra de foráneo en editar estudiante
document.addEventListener('DOMContentLoaded', function () {
    const foraneoCheckbox = document.getElementById('editForaneo');
    const extraFields = document.getElementById('editPatriaExtraFields');
    foraneoCheckbox.addEventListener('change', function() {
        if (this.checked) {
            extraFields.style.display = 'block';
            extraFields.style.maxHeight = '0';
            setTimeout(() => {
                extraFields.style.maxHeight = extraFields.scrollHeight + 'px';
            }, 10);
        } else {
            extraFields.style.maxHeight = '0';
            setTimeout(() => {
                extraFields.style.display = 'none';
            }, 400);
            // Limpiar los campos al ocultar
            document.getElementById('editDireccionTemporal').value = '';
            document.getElementById('editCuantoPagasResidencia').value = '';
            document.getElementById('editVecesSemana').value = '';
            document.getElementById('editTiempoViajeHoras').value = '';
            document.getElementById('editTiempoViajeMinutos').value = '';
            document.getElementById('editGastoDiarioPasaje').value = '';
            document.getElementsByName('edit_paga_residencia').forEach(r => r.checked = false);
            document.getElementsByName('edit_viaja_diario').forEach(r => r.checked = false);
        }
    });

    // Al cargar la página, deshabilitar los inputs dependientes
    document.getElementById('editCuantoPagasResidencia').setAttribute('disabled', true);
    document.getElementById('editVecesSemana').setAttribute('disabled', true);

    // Habilitar/deshabilitar el input de "¿Cuánto pagas?" según "¿Pagas residencia?"
    document.getElementsByName('edit_paga_residencia').forEach(function(radio) {
        radio.addEventListener('change', function() {
        const cuantoInput = document.getElementById('editCuantoPagasResidencia');
        if (this.value === 'si') {
            cuantoInput.removeAttribute('disabled');
        } else {
            cuantoInput.setAttribute('disabled', true);
        }
        });
    });

    // Habilitar/deshabilitar el input de "¿Cuántas veces a la semana?" según "¿Viajas a diario a clases?"
    document.getElementsByName('edit_viaja_diario').forEach(function(radio) {
        radio.addEventListener('change', function() {
        const vecesInput = document.getElementById('editVecesSemana');
        if (this.value === 'si') {
            vecesInput.removeAttribute('disabled');
        } else {
            vecesInput.setAttribute('disabled', true);
        }
        });
    });
});

 // Script para llenar el modal de detalles con los datos de la fila

 /**   ESTO SE TIENE QUE MEJORAR CON BD */
document.querySelectorAll('.btn-details[data-bs-target="#detailsStudentModal"]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const row = btn.closest('tr');
        // Tomar datos de la fila
        document.getElementById('detailsCedula').value = row.cells[0]?.textContent.trim() || '-';
        document.getElementById('detailsPrimerNombre').value = row.cells[1]?.textContent.trim() || '-';
        document.getElementById('detailsPrimerApellido').value = row.cells[2]?.textContent.trim() || '-';

        // Datos que no están en la tabla: poner valores de ejemplo o por defecto
        document.getElementById('detailsLapso').value = '2023-2';
        document.getElementById('detailsPNF').value = 'Informatica';
        document.getElementById('detailsSexo').value = 'Masculino';
        document.getElementById('detailsEmail').value = 'angel@email.com';
        document.getElementById('detailsSede').value = 'Sede Central';
        document.getElementById('detailsCondicion').value = 'Regular';
        document.getElementById('detailsFechaNacimiento').value = '2000-01-01';
        document.getElementById('detailsEdad').value = '23';
        document.getElementById('detailsTelefono').value = '04141234567';
        document.getElementById('detailsDireccion').value = 'Calle 123, Ciudad';
        document.getElementById('detailsProcedencia').value = 'Agua Blanca, Acarigua';
        // Foráneo y patria
        // Simulación: algunos estudiantes son foráneos según la cédula (ejemplo)
        const cedula = row.cells[0]?.textContent.trim() || '';
        // Por ejemplo, si la cédula termina en 2, 8 o 9, es foráneo
        const esForaneo = ['2', '8', '9'].includes(cedula.slice(-1));
        document.getElementById('detailsForaneo').value = esForaneo ? 'Sí' : 'No';
        document.getElementById('detailsPatria').value = 'Sí';

        // Mostrar/ocultar campos extra de foráneo
        const foraneoExtraFields = document.getElementById('foraneoExtraFields');
        if (esForaneo) {
            foraneoExtraFields.style.display = 'block';
            document.getElementById('detailsDireccionTemporal').value = 'Calle 456, Ciudad';
            document.getElementById('detailsPagaResidencia').value = 'Sí';
            document.getElementById('detailsCuantoPagasResidencia').value = '100 Bs.';
            document.getElementById('detailsViajaDiario').value = 'Sí';
            document.getElementById('detailsVecesSemana').value = '5';
            document.getElementById('detailsTiempoViaje').value = '1h 30m';
            document.getElementById('detailsGastoDiarioPasaje').value = '10 Bs.';
        } else {
            foraneoExtraFields.style.display = 'none';
            document.getElementById('detailsDireccionTemporal').value = '';
            document.getElementById('detailsPagaResidencia').value = '';
            document.getElementById('detailsCuantoPagasResidencia').value = '';
            document.getElementById('detailsViajaDiario').value = '';
            document.getElementById('detailsVecesSemana').value = '';
            document.getElementById('detailsTiempoViaje').value = '';
            document.getElementById('detailsGastoDiarioPasaje').value = '';
        }
    });
});

/* ======================  HASTA AQUI  ================================ */


document.addEventListener('DOMContentLoaded', function () {
    const viajaDiarioSi = document.getElementById('viajaDiarioSi');
    const viajaDiarioNo = document.getElementById('viajaDiarioNo');
    const vecesSemana = document.getElementById('vecesSemana');
    const tiempoViajeHoras = document.getElementById('tiempoViajeHoras');
    const tiempoViajeMinutos = document.getElementById('tiempoViajeMinutos');
    const gastoDiarioPasaje = document.getElementById('gastoDiarioPasaje');

    function toggleViajaDiarioFields() {
        if (viajaDiarioSi.checked) {
            vecesSemana.removeAttribute('disabled');
            tiempoViajeHoras.removeAttribute('disabled');
            tiempoViajeMinutos.removeAttribute('disabled');
            gastoDiarioPasaje.removeAttribute('disabled');
        } else {
            vecesSemana.setAttribute('disabled', true);
            tiempoViajeHoras.setAttribute('disabled', true);
            tiempoViajeMinutos.setAttribute('disabled', true);
            gastoDiarioPasaje.setAttribute('disabled', true);
            vecesSemana.value = '';
            tiempoViajeHoras.value = '';
            tiempoViajeMinutos.value = '';
            gastoDiarioPasaje.value = '';
        }
    }

    viajaDiarioSi.addEventListener('change', toggleViajaDiarioFields);
    viajaDiarioNo.addEventListener('change', toggleViajaDiarioFields);

    // Inicializar estado al cargar
    toggleViajaDiarioFields();
});

document.addEventListener('DOMContentLoaded', function () {
    const pagaResidenciaSi = document.getElementById('pagaResidenciaSi');
    const pagaResidenciaNo = document.getElementById('pagaResidenciaNo');
    const direccionTemporalDiv = document.getElementById('direccionTemporalDiv');
    const foraneoNoFields = document.getElementById('foraneoNoFields');
    const regForaneo = document.getElementById('regForaneo');
    const extraFields = document.getElementById('patriaExtraFields');

    function updateForaneoFields() {
        if (regForaneo.checked) {
            extraFields.style.display = 'block';
            // Expandir el contenedor para mostrar todo el contenido
            extraFields.style.maxHeight = extraFields.scrollHeight + 'px';

            // Mostrar solo Dirección Temporal si "Sí" en ¿Pagas residencia?
            if (pagaResidenciaSi.checked) {
                direccionTemporalDiv.style.display = 'block';
                foraneoNoFields.style.display = 'none';
            } else if (pagaResidenciaNo.checked) {
                direccionTemporalDiv.style.display = 'none';
                foraneoNoFields.style.display = 'block';
            } else {
                direccionTemporalDiv.style.display = 'none';
                foraneoNoFields.style.display = 'none';
            }

            // Ajustar maxHeight después de mostrar los campos internos
            setTimeout(() => {
                extraFields.style.maxHeight = extraFields.scrollHeight + 'px';
            }, 50);
        } else {
            extraFields.style.maxHeight = '0';
            setTimeout(() => {
                extraFields.style.display = 'none';
                direccionTemporalDiv.style.display = 'none';
                foraneoNoFields.style.display = 'none';
            }, 400);
        }
    }

    pagaResidenciaSi.addEventListener('change', updateForaneoFields);
    pagaResidenciaNo.addEventListener('change', updateForaneoFields);
    regForaneo.addEventListener('change', function() {
        // Limpiar radios y campos al desmarcar foráneo
        if (!this.checked) {
            pagaResidenciaSi.checked = false;
            pagaResidenciaNo.checked = false;
            document.getElementById('regDireccionTemporal').value = '';
        }
        updateForaneoFields();
    });

    // Inicializar al cargar
    updateForaneoFields();
});


document.addEventListener('DOMContentLoaded', function () {
    // Solo números y máximo 8 dígitos para cédula
    document.getElementById('regCedula').addEventListener('input', function() {
        validarInputVacio1(this);
    });

    // Solo números y máximo 11 dígitos para teléfono
    document.getElementById('regTelefono').addEventListener('input', function() {
        validarInputVacio1(this, true);
    });

    // Animación y mostrar/ocultar campos extra de foráneo
    const foraneoCheckbox = document.getElementById('regForaneo');
    const extraFields = document.getElementById('patriaExtraFields');
    foraneoCheckbox.addEventListener('change', function() {
        if (this.checked) {
            extraFields.style.display = 'block';
            // For animation: set max-height to 0 first, then to scrollHeight
            extraFields.style.maxHeight = '0';
            setTimeout(() => {
                extraFields.style.maxHeight = extraFields.scrollHeight + 'px';
            }, 10);
        } else {
            extraFields.style.maxHeight = '0';
            setTimeout(() => {
                extraFields.style.display = 'none';
            }, 400);
        }
    });

    /* Script para mostrar la edad del estudiante*/
    mostrarFecha('regFechaNacimiento', 'regEdad');

    // Habilitar/deshabilitar el input de "¿Cuántas veces a la semana?" según "¿Viajas a diario a clases?"
    document.getElementsByName('viaja_diario').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const vecesInput = document.getElementById('vecesSemana');
            if (this.value === 'si') {
                vecesInput.removeAttribute('disabled');
            } else {
                vecesInput.setAttribute('disabled', true);
            }
        });
    });

    //Hablitar hora o minutos si unos de los 2 son rellenados 
    const tiempoViajeHoras = document.getElementById('tiempoViajeHoras');
    const tiempoViajeMinutos = document.getElementById('tiempoViajeMinutos');

    tiempoViajeHoras.addEventListener('input', function() {
        // Solo permitir números y máximo 3 dígitos
        if (tiempoViajeHoras.value.length > 3) {
            tiempoViajeHoras.value = tiempoViajeHoras.value.slice(0, 3);
        }
        // Deshabilitar minutos si horas están rellenados
        if (tiempoViajeHoras.value !== '') {
            tiempoViajeMinutos.setAttribute("disabled", true);
        } else {
            tiempoViajeMinutos.removeAttribute("disabled");
        }
    });
    //Deshabilitar hora si minutos están rellenados
    tiempoViajeMinutos.addEventListener('input', function() {
        // Solo permitir números y máximo 3 dígitos
        if (tiempoViajeMinutos.value.length > 3) {
            tiempoViajeMinutos.value = tiempoViajeMinutos.value.slice(0, 3);
        }
        // Deshabilitar horas si minutos están rellenados
        if (tiempoViajeMinutos.value !== '') {
            tiempoViajeHoras.setAttribute("disabled", true);
        } else {
            tiempoViajeHoras.removeAttribute("disabled");
        }
    });

    // Validación y envío del formulario de registro del estudiante
    document.getElementById('registerStudentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let form = this;
        let valid = true;

        // Validar cédula
        const cedula = form.regCedula.value.trim();
        if (!/^\d{8}$/.test(cedula)) {
            form.regCedula.classList.add('is-invalid');
            valid = false;
        } else {
            form.regCedula.classList.remove('is-invalid');
        }

        //validar email con una expresión regular
        const email = form.regEmail.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        // Validar email
        if (email.trim() === '') {
            form.regEmail.classList.add('is-invalid');
            valid = false;
        } else if (!emailRegex.test(email)) {
            form.regEmail.classList.add('is-invalid');
            form.regEmail.nextElementSibling.textContent = 'Ingrese un email válido.';
            valid = false;
        } else {
            form.regEmail.classList.remove('is-invalid');
            form.regEmail.nextElementSibling.textContent = 'El campo no puede estar vacío.';
        }

        //Validar los campos requeridos
        valid = validarInputVacio(form.regNombre) && valid;
        valid = validarInputVacio(form.regApellido) && valid;
        valid = validarInputVacio(form.regSexo) && valid;
        valid = validarInputVacio(form.regTelefono) && valid;
        valid = validarInputVacio(form.regFechaNacimiento) && valid;
        valid = validarInputVacio(form.regCondicion) && valid;
        valid = validarInputVacio(form.regDireccion) && valid;
        valid = validarInputVacio(form.regProcedencia) && valid;

        // No validar campos extra de foráneo, ya que no son obligatorios

        if (!valid) return;

        // Confirmación con SweetAlert
        Swal.fire({
            title: '¿Registrar estudiante?',
            text: 'Verifique los datos antes de confirmar.',
            icon: 'question',
            confirmButtonText: 'Registrar',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '¡Registrado!',
                    text: 'El estudiante ha sido registrado.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                });
                // Cerrar modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('registerStudentModal'));
                modal.hide();
                form.reset();
                document.getElementById('regEdad').value = '';
                // Aquí puedes agregar la lógica para enviar los datos al backend
            }
        });
    });

    // Quitar la clase is-invalid al escribir

    //Variable para almacenar los ids de los inputs
    const inputIds = [
        'regCedula', 'regNombre', 'regApellido', 'regSexo', 'regTelefono',
        'regFechaNacimiento', 'regCondicion', 'regEmail', 'regDireccion',
        'regProcedencia', 'regDireccionTemporal', 'cuantoPagasResidencia',
        'vecesSemana', 'tiempoViajeHoras', 'tiempoViajeMinutos', 'gastoDiarioPasaje',
        'pagaResidenciaSi', 'pagaResidenciaNo', 'viajaDiarioSi', 'viajaDiarioNo'
    ];

    // Agregar evento de input a cada uno de los inputs
    inputIds.forEach(function(id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        }
        // Quitar la clase is-invalid al escribir en cualquiera de los dos inputs de tiempo de viaje
        if (id === 'tiempoViajeHoras' || id === 'tiempoViajeMinutos') {
            el.addEventListener('input', function() {
                document.getElementById('tiempoViajeHoras').classList.remove('is-invalid');
                document.getElementById('tiempoViajeMinutos').classList.remove('is-invalid');
            });
        }
        // quitar la clase is-invalid a los input radio de paga residencia
        if(id === 'pagaResidenciaSi' || id === 'pagaResidenciaNo'){
            el.addEventListener('change', function() {
                // Busca el feedback específico dentro del grupo de paga residencia
                const pagaResidenciaGroup = el.closest('.mb-3');
                const cuantoDiv = pagaResidenciaGroup ? pagaResidenciaGroup.querySelector('.invalid-feedback') : null;
                if (cuantoDiv) {
                    cuantoDiv.style.display = this.checked ? 'none' : 'block';
                }
            });
        }

        //quitar la clase is-invalid a los input radio de viaja diario
        if(id === 'viajaDiarioSi' || id === 'viajaDiarioNo'){
            el.addEventListener('change', function() {
                // Busca el feedback específico dentro del grupo de viaja diario
                const viajaDiarioGroup = el.closest('.mb-3');
                const cuantoDiv = viajaDiarioGroup ? viajaDiarioGroup.querySelector('.invalid-feedback') : null;
                if (cuantoDiv) {
                    cuantoDiv.style.display = this.checked ? 'none' : 'block';
                }
            });
        }

    });
});
