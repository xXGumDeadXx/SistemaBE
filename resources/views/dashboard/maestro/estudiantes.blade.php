@extends('layouts.layoutDash')
@section('title', 'Estudiantes')
@section('links')
    <a href="{{ route('estudiantes') }}" class="navbar-brand nav-link">Estudiantes/</a>
    
@endsection

@section('content')
    @section('titulo', 'Estudiantes')
    <script src="{{ asset('js/estudiante.js') }}"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="contenedor">
        <div class="d-flex justify-content-end my-4" style="max-width: 400px; margin-left: auto; flex-direction: column;">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar Persona..." id="searchInput">
                <button class="btn btn-primary" type="button" id="searchButton">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
            <div class="d-flex justify-content-end">
                <button style="text-wrap: nowrap; width: 190px; display: flex; margin-top: 10px;" type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#registerStudentModal">
                    <i class="bi bi-person-plus"></i> Registrar Estudiante
                </button>
            </div>
        </div>

        <div class="table-container ">
            @if ($estudiantes->isEmpty())
                {{-- Si no hay estudiantes, mostrar mensaje --}}
                <div class="alert alert-info text-center" role="alert">
                    No hay estudiantes registrados.
                </div>
            @else
                <table class="table table-striped table-bordered my-2" id="sortable-table">
                <thead>
                    <tr>
                        <th scope="col" onclick="sortTable(0)">Cedula ↑</th>
                        <th scope="col" onclick="sortTable(1)">Nombre ↑</th>
                        <th scope="col" onclick="sortTable(2)">Apellido ↑</th>

                        <th scope="col" style="width: 1%; white-space: nowrap;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estudiantes as $estudiante)
                    <tr>
                        <td>{{ $estudiante->cedula_persona }}</td>
                        <td>{{ $estudiante->nombre_persona }}</td>
                        <td>{{ $estudiante->apellido_persona }}</td>
    
                        <td>
                            <button class="btn-minimal btn-details" id="detailsButton" data-bs-toggle="modal" data-bs-target="#detailsStudentModal">
                                <img src="{{ asset('icons/details.svg') }}" alt="Icono de editar" class="icon-edit">
                                Ver Detalles
                            </button>
                            <button class="btn-minimal btn-edit" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <img src="{{ asset('icons/edit_blue.svg') }}" alt="Icono de editar" class="icon-edit">
                                Editar
                            </button>
                            <button class="btn-minimal btn-delete" id="deleteButton" data-cedula="{{ $estudiante->cedula_persona }}">
                                <img src="{{ asset('icons/delete_red.svg') }}" alt="Icono de eliminar" class="icon-delete">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            {{-- SECCION DE BOTONES DE PRIMERA Y ULTIMA PAGINA --}}
            <div class="d-flex justify-content-between align-items-center my-4">
                <div>
                    <span>Mostrando {{ $estudiantes->count() }} de {{ $estudiantes->total() }} estudiantes</span>
                </div>
                <div>
                    <span>Página {{ $estudiantes->currentPage() }} de {{ $estudiantes->lastPage() }}</span>
                </div>
            </div>
            <div class="d-flex justify-content-between my-2 gap-2">
                @if ($estudiantes->onFirstPage() && $estudiantes->lastPage() > 1)
                    <a href="{{ $estudiantes->url($estudiantes->lastPage()) }}" class="btn btn-outline-primary">
                        Ir a última página
                    </a>
                @elseif ($estudiantes->currentPage() == $estudiantes->lastPage() && $estudiantes->lastPage() > 1)
                    <a href="{{ $estudiantes->url(1) }}" class="btn btn-outline-primary">
                        Ir a primera página
                    </a>
                @elseif ($estudiantes->lastPage() > 1)
                    <a href="{{ $estudiantes->url(1) }}" class="btn btn-outline-primary">
                        Ir a primera página
                    </a>
                    <a href="{{ $estudiantes->url($estudiantes->lastPage()) }}" class="btn btn-outline-primary">
                        Ir a última página
                    </a>
                @endif
            </div>
        </div>
        </div>
        {{-- Paginación --}}
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{-- Botón Anterior --}}
                @if ($estudiantes->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link" aria-hidden="true">&laquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $estudiantes->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                @endif

                {{-- Enlaces de páginas --}}
                @foreach ($estudiantes->getUrlRange(1, $estudiantes->lastPage()) as $page => $url)
                    <li class="page-item {{ $estudiantes->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Botón Siguiente --}}
                @if ($estudiantes->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $estudiantes->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link" aria-hidden="true">&raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    
    <script>
        // Script para el botón de eliminar
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.querySelectorAll('button[id="deleteButton"]').forEach(function(btn) {
            //Recoger el nombre y el apellido del estudiante a eliminar en la fila que esta en el boton
            const row = btn.closest('tr');

            //Guardar los datos del estudiante a eliminar en variables
            const nombre = row.cells[1].textContent.trim();
            const apellido = row.cells[2].textContent.trim();
            //Agarramos la id del estudiante
            const id = btn.getAttribute('data-cedula');

            btn.addEventListener('click', function(e) {
                //Mostrar el modal de confirmación con los datos del estudiante a eliminar
                Swal.fire({
                    title: `¿Estás seguro que quieres eliminar a ${nombre} ${apellido}?`,
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    reverseButtons: true
                })
                .then((result) => {
                    //Si el usuario confirma la eliminación, enviar la solicitud a eliminar al backend
                    if (result.isConfirmed) {
                        fetch(`/dashboard/estudiantes/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            },
                        })
                        .then(function(response) {
                            if (!response.ok) throw new Error('Error al eliminar el estudiante');
                            return response.json();
                        })
                        .then(data => {
                            //Proceso de eliminar estudiante
                            row.remove();
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: `El estudiante ${nombre} ${apellido} ha sido eliminado.`,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo eliminar el estudiante. Inténtalo de nuevo más tarde.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar',
                            });
                        });
                    }
                });
            });
        });
    </script>
    <!-- Modal de editar estudiante -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 450px;">
            <div class="modal-content">
                <form id="editStudentForm" novalidate>
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Datos del Estudiante</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="editCedula" class="form-label">Cédula</label>
                                    <input type="text" class="form-control" id="editCedula" name="cedula" required pattern="\d{1,10}" maxlength="10" style="max-width: 220px;" placeholder="Ej: 1234567890">
                                    <div class="invalid-feedback">
                                        Solo números, máximo 10 dígitos.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editNombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="editNombre" name="nombre" required placeholder="Ej: Angel">
                                    <div class="invalid-feedback">
                                        El nombre no puede estar vacío.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editSegundoNombre" class="form-label">Segundo Nombre</label>
                                    <input type="text" class="form-control" id="editSegundoNombre" name="segundo_nombre" placeholder="Ej: Luis">
                                </div>
                                <div class="mb-3">
                                    <label for="editApellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="editApellido" name="apellido" required placeholder="Ej: Hernandez">
                                    <div class="invalid-feedback">
                                        El apellido no puede estar vacío.
                                    </div>
                                </div>    
                                <div class="mb-3">
                                    <label for="editSegundoApellido" class="form-label">Segundo Apellido</label>
                                    <input type="text" class="form-control" id="editSegundApellido" name="segundo_apellido" placeholder="Ej: Colmenarez"> 
                                </div>
                                <div class="mb-3">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-6">
                                            <label for="editSexo" class="form-label">Género</label>
                                            <select class="form-select" id="editSexo" name="sexo" required>
                                                <option selected disabled>Seleccione Género</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Ingrese el Género.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editTelefono" class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" id="editTelefono" name="telefono" required placeholder="Ej: 04141234567" pattern="\d{11}" maxlength="11" style="max-width: 220px;">
                                            <div class="invalid-feedback">
                                                Ingrese un teléfono válido.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="row g-2 align-items-start">
                                        <div class="col-md-6">
                                            <label for="editFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" id="editFechaNacimiento" name="fecha_nacimiento" required placeholder="Ej: 2000-01-01">
                                            <div class="invalid-feedback">
                                                Fecha Invalida.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editEdad" class="form-label">Edad</label>
                                            <input type="text" class="form-control" id="editEdad" name="edad" readonly placeholder="Edad" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editCondicion" class="form-label">Condición</label>
                                    <select class="form-select" id="editCondicion" name="condicion" required>
                                        <option selected disabled>Seleccione Condición</option>
                                        <option value="Regular">Regular</option>
                                        <option value="Per (Repitencia)">Repitencia</option>
                                        <option value="Congelado">Congelado</option>
                                        <option value="Reingreso">Reingreso</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ingrese la condición.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required placeholder="Ej: angel@email.com">
                                    <div class="invalid-feedback">
                                        El campo no puede estar vacío.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editDireccion" class="form-label">Procedencia</label>
                                    <select class="form-select" id="editDireccion" name="direccion" required>
                                        <option value="" selected disabled>Seleccione un estado</option>
                                        <option value="Amazonas">Amazonas</option>
                                        <option value="Anzoátegui">Anzoátegui</option>
                                        <option value="Apure">Apure</option>
                                        <option value="Aragua">Aragua</option>
                                        <option value="Barinas">Barinas</option>
                                        <option value="Bolívar">Bolívar</option>
                                        <option value="Carabobo">Carabobo</option>
                                        <option value="Cojedes">Cojedes</option>
                                        <option value="Delta Amacuro">Delta Amacuro</option>
                                        <option value="Distrito Capital">Distrito Capital</option>
                                        <option value="Falcón">Falcón</option>
                                        <option value="Guárico">Guárico</option>
                                        <option value="Lara">Lara</option>
                                        <option value="Mérida">Mérida</option>
                                        <option value="Miranda">Miranda</option>
                                        <option value="Monagas">Monagas</option>
                                        <option value="Nueva Esparta">Nueva Esparta</option>
                                        <option value="Portuguesa">Portuguesa</option>
                                        <option value="Sucre">Sucre</option>
                                        <option value="Táchira">Táchira</option>
                                        <option value="Trujillo">Trujillo</option>
                                        <option value="La Guaira">La Guaira</option>
                                        <option value="Yaracuy">Yaracuy</option>
                                        <option value="Zulia">Zulia</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Debe seleccionar un estado de Procedencia.
                                    </div>
                                </div>
                                <div id="portuguesaFields" style="display: none;">
                                    <div class="mb-3">
                                        <label for="editMunicipio" class="form-label">Municipio</label>
                                        <select class="form-select" id="editMunicipio" name="municipio" required>
                                            <option value="" selected disabled>Seleccione un Municipio</option>
                                            <option value="Araure">Araure</option>
                                            <option value="Esteller">Esteller</option>
                                            <option value="Guanare">Guanare</option>
                                            <option value="Guanarito">Guanarito</option>
                                            <option value="Ospino">Ospino</option>
                                            <option value="Paez">Paez</option>
                                            <option value="Sucre">Sucre</option>
                                            <option value="Turen">Turen</option>
                                            <option value="Monseñor">Monseñor Jose V. de Unda</option>
                                            <option value="aguaBlanca">Agua Blanca</option>
                                            <option value="Papelon">Papelón</option>
                                            <option value="sanGenaro">San Genaro de Boconoito</option>
                                            <option value="sanRafael">San Rafael de Onoto</option>
                                            <option value="santaRosalia">Santa Rosalia</option>
                                        </select>    
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editSector" class="form-label">Sector/Urbanización</label>
                                    <input type="text" class="form-control" id="editSector" name="sector" placeholder="Ej: Centro">
                                </div>
                                <div class="mb-3">
                                    <label for="editCalle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="editCalle" name="calle" placeholder="Ej: Calle 5">
                                </div>
                                <div class="mb-3">
                                    <label for="editCasa" class="form-label">Número Casa</label>
                                    <input type="text" class="form-control" id="editCasa" name="casa" placeholder="Ej: Casa 12">
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="editForaneo" name="foraneo">
                                    <label class="form-check-label" for="editForaneo">¿Es Foráneo?</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="editPatria" name="patria">
                                    <label class="form-check-label" for="editPatria">¿Está registrado en patria?</label>
                                </div>
                                
                                <!--  Campos extra de foraneo -->
                                <div id="editPatriaExtraFields" style="display: none; overflow: hidden; max-height: 0; transition: max-height 0.4s ease;">
                                    <div class="mb-3">
                                        <label for="editDireccionTemporal" class="form-label">Dirección Temporal</label>
                                        <input type="text" class="form-control" id="editDireccionTemporal" name="direccion_temporal" placeholder="Ej: Calle 456, Ciudad" maxlength="150">
                                        <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                            La dirección temporal no puede estar vacía.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">¿Pagas residencia?</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="edit_paga_residencia" id="editPagaResidenciaSi" value="si">
                                                <label class="form-check-label" for="editPagaResidenciaSi">Sí</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="edit_paga_residencia" id="editPagaResidenciaNo" value="no">
                                                <label class="form-check-label" for="editPagaResidenciaNo">No</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                            Seleccione una opción.
                                        </div>
                                    </div>
                                    <div class="mb-3" id="editCuantoPagasResidenciaDiv">
                                        <label for="editCuantoPagasResidencia" class="form-label">¿Cuánto pagas?</label>
                                        <input type="number" class="form-control" id="editCuantoPagasResidencia" name="cuanto_pagas_residencia" min="0" placeholder="Monto en Bs.">
                                        <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                            Ingrese un monto válido.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">¿Viajas a diario a clases?</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="edit_viaja_diario" id="editViajaDiarioSi" value="si">
                                                <label class="form-check-label" for="editViajaDiarioSi">Sí</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="edit_viaja_diario" id="editViajaDiarioNo" value="no">
                                                <label class="form-check-label" for="editViajaDiarioNo">No</label>
                                            </div>
                                            <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                                Seleccione una opción.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3" id="editVecesSemanaDiv">
                                        <label for="editVecesSemana" class="form-label">¿Cuántas veces a la semana?</label>
                                        <input type="number" class="form-control" id="editVecesSemana" name="veces_semana" min="1" max="9" maxlength="1" required placeholder="Ej: 5">
                                        <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                            El campo es obligatorio y debe ser un solo dígito entre 1 y 7.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">¿Cuánto tiempo inviertes en el viaje?</label>
                                        <div class="row g-2">
                                            <div class="col">
                                                <input type="number" class="form-control" id="editTiempoViajeHoras" name="tiempo_viaje_horas" min="0" max="24" placeholder="Horas">
                                                <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                                    El campo no puede estar vacío.
                                                </div>
                                            </div>
                                            <div class="col">
                                                <input type="number" class="form-control" id="editTiempoViajeMinutos" name="tiempo_viaje_minutos" min="0" max="59" placeholder="Minutos">
                                                <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                                    El campo no puede estar vacío.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editGastoDiarioPasaje" class="form-label">Gasto diario en pasaje</label>
                                        <input type="text" class="form-control" id="editGastoDiarioPasaje" name="gasto_diario_pasaje" min="0" placeholder="Monto en Bs.">
                                        <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                            Ingrese un monto válido.
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-minimal btn-cancel" data-bs-dismiss="modal">
                                        <img src="{{ asset('icons/close.svg') }}" alt="Icono de cancelar" class="icon-close">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn-minimal btn-save">
                                        <img src="{{ asset('icons/save_green.svg') }}" alt="Icono de guardar" class="icon-save">
                                        Actualizar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const estadoSelect = document.getElementById('editDireccion'); // Select de estados
    const portuguesaFieldsDiv = document.getElementById('portuguesaFields'); // El div que se mostrara

    portuguesaFieldsDiv.style.display = 'none';

    estadoSelect.addEventListener('change', function() {
        // Si el valor seleccionado es 'Portuguesa'
        if (estadoSelect.value === 'Portuguesa') {
            portuguesaFieldsDiv.style.display = 'block'; // Muestra el div
        } else {
            portuguesaFieldsDiv.style.display = 'none'; // Oculta el div si se selecciona otro estado
        }
    });
});
    </script>
    <!-- Modal de detalles del estudiante -->
    <div class="modal fade" id="detailsStudentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailsStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 450px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detailsStudentModalLabel">Detalles del Estudiante</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="detailsCedula" class="form-label">Cédula</label>
                                <input type="text" class="form-control" id="detailsCedula" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="detailsNombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="detailsNombre" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="detailsSegundoNombre" class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control" id="detailsSegundoNombre" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="detailsApellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="detailsApellido" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="detailsSegundoApellido" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control" id="detailsSegundoApellido" readonly>
                            </div>
                            <div class="mb-3">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-6">
                                        <label for="detailsSexo" class="form-label">Género</label>
                                        <input type="text" class="form-control" id="detailsSexo" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="detailsTelefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="detailsTelefono" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row g-2 align-items-start">
                                    <div class="col-md-6">
                                        <label for="detailsFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="detailsFechaNacimiento" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="detailsEdad" class="form-label">Edad</label>
                                        <input type="text" class="form-control" id="detailsEdad" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="detailsPnf" class="form-label">Pnf</label>
                                <input type="email" class="form-control" id="detailsPnf" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="detailsCondicion" class="form-label">Condición</label>
                                <input type="text" class="form-control" id="detailsCondicion" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="detailsEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="detailsEmail" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="detailsDireccion" class="form-label">Procedencia</label>
                                <input type="text" class="form-control" id="detailsDireccion" readonly>
                            </div>
                            <div class="mb-3">
                                    <label for="detailsSector" class="form-label">Sector/Urbanizacion</label>
                                    <input type="text" class="form-control" id="detailsSector" name="sector" placeholder="Ej: Centro">
                                </div>
                                <div class="mb-3">
                                    <label for="detailsCalle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="detailsCalle" name="calle" placeholder="Ej: Calle 5">
                                </div>
                                <div class="mb-3">
                                    <label for="detailsCasa" class="form-label">Casa</label>
                                    <input type="text" class="form-control" id="detailsCasa" name="casa" placeholder="Ej: Casa 12">
                                </div>
                                </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="detailsForaneo" disabled>
                                <label class="form-check-label" for="detailsForaneo">¿Es Foráneo?</label>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="detailsPatria" disabled>
                                <label class="form-check-label" for="detailsPatria">¿Está registrado en patria?</label>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-minimal btn-cancel" data-bs-dismiss="modal">
                                    <img src="{{ asset('icons/close.svg') }}" alt="Icono de cancelar" class="icon-close">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //recuperar todos los input de detalles en una variables
        const detailsCedula = document.getElementById('detailsCedula');
        const detailsNombre = document.getElementById('detailsNombre');
        const detailsSegundoNombre = document.getElementById('detailsSegundoNombre');
        const detailsApellido = document.getElementById('detailsApellido');
        const detailsSegundoApellido = document.getElementById('detailsSegundoApellido');
        const detailsSexo = document.getElementById('detailsSexo');
        const detailsTelefono = document.getElementById('detailsTelefono');
        const detailsFechaNacimiento = document.getElementById('detailsFechaNacimiento');
        const detailsEdad = document.getElementById('detailsEdad');
        const detailsCondicion = document.getElementById('detailsCondicion');
        const detailsEmail = document.getElementById('detailsEmail');
        const detailsPnf = document.getElementById('detailsPnf')
        const detailsPatria = document.getElementById('detailsPatria');


        //recuperar todos los botones de detalles
        document.querySelectorAll("button[id='detailsButton']").forEach(function(btn) {
            btn.addEventListener('click', ()=>{
                const row = btn.closest('tr');
                //recuperar los datos del estudiante de la fila
                const cedula = row.cells[0].textContent.trim();
                //Hacer un fetch a la ruta
                fetch(`/dashboard/estudiantes/${cedula}/detalle`)
                .then(response =>{
                    //si la respuesa no es ok
                    if (!response.ok) throw new Error('Error al obtener los detalles del estudiante');

                    return response.json();
                })
                .then(estudiante =>{
                    const detallesEstudiante = estudiante.data;
                    console.log(detallesEstudiante);
                    //Aqui comienza la magia 
                    detailsCedula.value = detallesEstudiante.cedula;
                    detailsNombre.value = detallesEstudiante.nombre;
                    detailsSegundoNombre.value = detallesEstudiante.segundo_nombre;
                    detailsApellido.value = detallesEstudiante.apellido;
                    detailsSegundoApellido.value = detallesEstudiante.segundo_apellido;
                    detailsSexo.value = detallesEstudiante.genero;
                    detailsTelefono.value = detallesEstudiante.telefono;
                    detailsFechaNacimiento.value = detallesEstudiante.fecha_nacimiento;
                    detailsEdad.value = detallesEstudiante.edad;
                    detailsEmail.value = detallesEstudiante.email;
                    detailsPnf.value = detallesEstudiante.pnf

                    if(detallesEstudiante.regis_patria === 1){
                        detailsPatria.checked = true;
                    } else {
                        detailsPatria.checked = false;
                    }


                })
            })
        });
    </script>
    <!-- Modal de registro de estudiante -->
    <div class="modal fade" id="registerStudentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registerStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 450px;">
            <div class="modal-content">
                <form id="registerStudentForm" novalidate>
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="registerStudentModalLabel">Registrar Nuevo Estudiante</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="regCedula" class="form-label" id="cedulaLimite">Cédula</label>
                                    <input type="number" class="form-control" id="regCedula" name="cedula" required pattern="\d{1,10}" maxlength="10" style="max-width: 220px;" placeholder="Ej: 1234567890">
                                    <div class="invalid-feedback">
                                        Solo números, máximo 10 dígitos.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="regNombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="regNombre" name="nombre" required placeholder="Ej: Angel">
                                    <div class="invalid-feedback">
                                        El nombre no puede estar vacío.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="regSegundoNombre" class="form-label">Segundo Nombre</label>
                                    <input type="text" class="form-control" id="regSegundoNombre" name="SegundoNombre" placeholder="Ej: Luis">
                                </div>
                                <div class="mb-3">
                                    <label for="regApellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="regApellido" name="apellido" required placeholder="Ej: Hernandez">
                                    <div class="invalid-feedback">
                                        El apellido no puede estar vacío.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="regSegundoApellido" class="form-label">Segundo Apellido</label>
                                    <input type="text" class="form-control" id="regSegundoApellido" name="SegundoApellido" placeholder="Ej: Colmenarez">
                                </div>
                                <div class="mb-3">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-6">
                                            <label for="regSexo" class="form-label">Género</label>
                                            <select class="form-select" id="regSexo" name="sexo" required>
                                                <option value="" selected disabled>Seleccione Género</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Ingrese el Género.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="regTelefono" class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" id="regTelefono" name="telefono" required placeholder="Ej: 04141234567" pattern="\d{11}" maxlength="11" style="max-width: 220px;">
                                            <div class="invalid-feedback">
                                                Ingrese un teléfono válido.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="row g-2 align-items-start">
                                        <div class="col-md-6">
                                            <label for="regFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" id="regFechaNacimiento" name="fecha_nacimiento" required placeholder="Ej: 2000-01-01">
                                            <div class="invalid-feedback">
                                                Fecha Invalida.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="regEdad" class="form-label">Edad</label>
                                            <input type="text" class="form-control" id="regEdad" name="edad" readonly placeholder="Edad" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="regCondicion" class="form-label">Condición</label>
                                    <select class="form-select" id="regCondicion" name="condicion" required>
                                        <option value="" selected disabled>Seleccione Condición</option>
                                        <option value="Regular">Regular</option>
                                        <option value="Per (Repitencia)">Per (Repitencia)</option>
                                        <option value="Congelado">Congelado</option>
                                        <option value="Reingreso">Reingreso</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Ingrese la condición.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="regEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="regEmail" name="email" required placeholder="Ej: angel@email.com">
                                    <div class="invalid-feedback">
                                        El campo no puede estar vacío.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="regisDireccion" class="form-label">Procedencia</label>
                                    <select class="form-select" id="regisDireccion" name="direccion" required>
                                        <option value="" selected disabled>Seleccione un estado</option>
                                        <option value="Amazonas">Amazonas</option>
                                        <option value="Anzoátegui">Anzoátegui</option>
                                        <option value="Apure">Apure</option>
                                        <option value="Aragua">Aragua</option>
                                        <option value="Barinas">Barinas</option>
                                        <option value="Bolívar">Bolívar</option>
                                        <option value="Carabobo">Carabobo</option>
                                        <option value="Cojedes">Cojedes</option>
                                        <option value="Delta Amacuro">Delta Amacuro</option>
                                        <option value="Distrito Capital">Distrito Capital</option>
                                        <option value="Falcón">Falcón</option>
                                        <option value="Guárico">Guárico</option>
                                        <option value="Lara">Lara</option>
                                        <option value="Mérida">Mérida</option>
                                        <option value="Miranda">Miranda</option>
                                        <option value="Monagas">Monagas</option>
                                        <option value="Nueva Esparta">Nueva Esparta</option>
                                        <option value="Portuguesa">Portuguesa</option>
                                        <option value="Sucre">Sucre</option>
                                        <option value="Táchira">Táchira</option>
                                        <option value="Trujillo">Trujillo</option>
                                        <option value="La Guaira">La Guaira</option>
                                        <option value="Yaracuy">Yaracuy</option>
                                        <option value="Zulia">Zulia</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Debe seleccionar un estado de Procedencia.
                                    </div>
                                </div>    
                                <div id="portuguesaFieldsRegis" style="display: none;">
                                    <div class="mb-3">
                                        <label for="regisMunicipio" class="form-label">Municipio</label>
                                        <select class="form-select" id="regisMunicipio" name="municipio" required>
                                            <option value="" selected disabled>Seleccione un Municipio</option>
                                            <option value="Araure">Araure</option>
                                            <option value="Esteller">Esteller</option>
                                            <option value="Guanare">Guanare</option>
                                            <option value="Guanarito">Guanarito</option>
                                            <option value="Ospino">Ospino</option>
                                            <option value="Paez">Paez</option>
                                            <option value="Sucre">Sucre</option>
                                            <option value="Turen">Turen</option>
                                            <option value="Monseñor">Monseñor Jose V. de Unda</option>
                                            <option value="aguaBlanca">Agua Blanca</option>
                                            <option value="Papelon">Papelón</option>
                                            <option value="sanGenaro">San Genaro de Boconoito</option>
                                            <option value="sanRafael">San Rafael de Onoto</option>
                                            <option value="santaRosalia">Santa Rosalia</option>
                                        </select>    
                                    </div>
                                </div>                                                              
                                <div class="mb-3">
                                    <label for="regSector" class="form-label">Sector/Urbanizacion</label>
                                    <input type="text" class="form-control" id="regSector" name="sector" placeholder="Ej: Centro">
                                </div>
                                <div class="mb-3">
                                    <label for="regCalle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="regCalle" name="calle" placeholder="Ej: Calle 5">
                                </div>
                                <div class="mb-3">
                                    <label for="regCasa" class="form-label">Casa</label>
                                    <input type="text" class="form-control" id="regCasa" name="casa" placeholder="Ej: Casa 12">
                                </div>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="regPatria" name="patria">
                                    <label class="form-check-label" for="regPatria">¿Está registrado en patria?</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="regForaneo" name="foraneo">
                                    <label class="form-check-label" for="regForaneo">¿Es Foráneo?</label>
                                </div>
                                <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Obtenemos una referencia al select de estados
    const estadoSelectRegis = document.getElementById('regisDireccion');

    // Obtenemos una referencia al div que contiene el select de municipios de Portuguesa
    const portuguesaFieldsDivRegis = document.getElementById('portuguesaFieldsRegis');

    // Aseguramos que el div de municipios de Portuguesa esté oculto al cargar la página
    // Esto es crucial para que no se muestre por defecto si el CSS no lo oculta ya.
    portuguesaFieldsDivRegis.style.display = 'none';

    // Agregamos un "escuchador de eventos" al select de estados.
    // Cada vez que el valor del select cambie, se ejecutará la función.
    estadoSelectRegis.addEventListener('change', function() {
        // Verificamos si el valor seleccionado en el select de estados es "Portuguesa"
        if (estadoSelectRegis.value === 'Portuguesa') {
            // Si es Portuguesa, mostramos el div de los campos de Portuguesa
            portuguesaFieldsDivRegis.style.display = 'block';
        } else {
            // Si se selecciona cualquier otro estado, ocultamos el div
            portuguesaFieldsDivRegis.style.display = 'none';
        }
    });
});
    </script>
                                
                                <!--  Campos extra de foraneo -->
                                <div id="patriaExtraFields" style="display: none; overflow: hidden; max-height: 0; transition: max-height 0.4s ease;">
                                    <div class="mb-3">
                                        <label class="form-label">¿Pagas residencia?</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="paga_residencia" id="pagaResidenciaSi" value="si" required>
                                                <label class="form-check-label" for="pagaResidenciaSi">Sí</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="paga_residencia" id="pagaResidenciaNo" value="no" required>
                                                <label class="form-check-label" for="pagaResidenciaNo">No</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                            Seleccione una opción.
                                        </div>
                                    </div>
                                    <div class="mb-3" id="direccionTemporalDiv" style="display: none;">
                                        <label for="regDireccionTemporal" class="form-label">Dirección Temporal</label>
                                        <input type="text" class="form-control" id="regDireccionTemporal" name="direccion_temporal" placeholder="Ej: Calle 456, Ciudad" maxlength="150">
                                        <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                            La dirección temporal no puede estar vacía.
                                        </div>
                                    </div>

                                    <div id="foraneoNoFields" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">¿Viajas a diario a clases?</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="viaja_diario" id="viajaDiarioSi" value="si" required>
                                                    <label class="form-check-label" for="viajaDiarioSi">Sí</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="viaja_diario" id="viajaDiarioNo" value="no">
                                                    <label class="form-check-label" for="viajaDiarioNo">No</label>
                                                </div>
                                                <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                                    Seleccione una opción.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3" id="vecesSemanaDiv">
                                            <label for="vecesSemana" class="form-label">¿Cuántas veces a la semana?</label>
                                            <input type="number" class="form-control" id="vecesSemana" name="veces_semana" min="1" max="7" placeholder="Ej: 5" disabled>
                                            <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                                El campo no puede estar vacío.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">¿Cuánto tiempo inviertes en el viaje?</label>
                                            <div class="row g-2">
                                                <div class="col">
                                                    <input type="number" class="form-control" id="tiempoViajeHoras" name="tiempo_viaje_horas" min="0" max="24" placeholder="Horas" disabled>
                                                    <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                                        El campo no puede estar vacío.
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <input type="number" class="form-control" id="tiempoViajeMinutos" name="tiempo_viaje_minutos" min="0" max="59" placeholder="Minutos" disabled>
                                                    <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                                        El campo no puede estar vacío.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="gastoDiarioPasaje" class="form-label">Gasto diario en pasaje</label>
                                            <input type="text" class="form-control" id="gastoDiarioPasaje" name="gasto_diario_pasaje" min="0" placeholder="Monto en Bs." disabled>
                                            <div class="invalid-feedback" style="white-space: normal; word-break: break-word;">
                                                Ingrese un monto válido.
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-minimal btn-cancel" data-bs-dismiss="modal">
                                        <img src="{{ asset('icons/close.svg') }}" alt="Icono de cancelar" class="icon-close">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn-minimal btn-save">
                                        <img src="{{ asset('icons/save_green.svg') }}" alt="Icono de guardar" class="icon-save">
                                        Registrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
     
@endsection
