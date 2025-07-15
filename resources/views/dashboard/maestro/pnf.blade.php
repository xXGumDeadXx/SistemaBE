@extends('layouts.layoutDash')
@section('title', 'PNF')
@section('links')
    <a href="{{ route('pnf') }}" class="navbar-brand nav-link">PNF/</a>
@endsection

@section('content')
    @section('titulo', 'PNF')
    @vite(['resources/js/dashboard/pnf.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="contenedor">
        <div class="d-flex justify-content-end my-4" style="max-width: 400px; margin-left: auto; flex-direction: column;">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar Pnf..." id="searchInput">
                <button class="btn btn-primary" type="button" id="searchButton">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
            <div class="d-flex justify-content-end">
                <button style="text-wrap: nowrap; width: 130px; display: flex; margin-top: 10px;" type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#registerStudentModal">
                    <i class="bi bi-person-plus"></i> Registrar PNF
                </button>
            </div>
        </div>
        <div class="table-container">
            @if ($pnfs->isEmpty())
                {{-- Si no hay estudiantes, mostrar mensaje --}}
                <table class="table table-striped table-bordered my-2">
                    <thead>
                        <tr>
                            <th scope="col">PNF</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center">No hay PNF registrados.</td>
                        </tr>
                    </tbody>
                </table>
            @else
            <table class="table table-striped table-bordered my-2" id="sortable-table">
                <thead>
                    <tr>
                        <th scope="col" onclick="sortTable(0)">PNF ↑</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pnfs as $pnf)
                    <tr>
                        <td>{{$pnf->nombre_pnf}}</td>
                        <td>
                            <button class="btn-minimal btn-edit" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="editarButton"data-pnf-id="{{ $pnf->id_pnf }}">
                                <img src="{{ asset('icons/edit_blue.svg') }}" alt="Icono de editar" class="icon-edit">
                                Editar
                            </button>
                            <button class="btn-minimal btn-delete" id="deleteButton" data-pnf-id="{{ $pnf->id_pnf }}">
                                <img src="{{ asset('icons/delete_red.svg') }}" alt="Icono de eliminar" class="icon-delete">
                                Eliminar
                            </button>
                        </td>
                        <td>
                            @if($pnf->id_estatus === 1)
                                <div class="form-check form-switch d-flex align-items-center" style="margin-bottom: 0;">
                                    <input class="form-check-input sede-switch sedeCentralSwitch" type="checkbox" id="sedeCentralSwitch" checked style="width: 2.5em; height: 1.3em;" data-sede="Informatica" data-pnf-id="{{ $pnf->id_pnf }}">
                                    <label class="form-check-label ms-2" for="sedeCentralSwitch" style="user-select: none;">
                                        Activo
                                    </label>
                                </div>
                            @else
                                <div class="form-check form-switch d-flex align-items-center" style="margin-bottom: 0;">
                                    <input class="form-check-input sede-switch sedeCentralSwitch" type="checkbox" id="sedeCentralSwitch" style="width: 2.5em; height: 1.3em;" data-sede="Informatica" data-pnf-id="{{ $pnf->id_pnf }}">
                                    <label class="form-check-label ms-2" for="sedeCentralSwitch" style="user-select: none;">
                                        Inactivo
                                    </label>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif    
                </tbody>
            </table>
            {{-- SECCION DE BOTONES DE PRIMERA Y ULTIMA PAGINA --}}
            <div class="d-flex justify-content-between align-items-center my-4">
                <div>
                    <span>Mostrando {{ $pnfs->count() }} de {{ $pnfs->total() }} pnfs</span>
                </div>
                <div>
                    <span>Página {{ $pnfs->currentPage() }} de {{ $pnfs->lastPage() }}</span>
                </div>
            </div>
            <div class="d-flex justify-content-between my-2 gap-2">
                @if ($pnfs->onFirstPage() && $pnfs->lastPage() > 1)
                    <a href="{{ $pnfs->url($pnfs->lastPage()) }}" class="btn btn-outline-primary">
                        Ir a última página
                    </a>
                @elseif ($pnfs->currentPage() == $pnfs->lastPage() && $pnfs->lastPage() > 1)
                    <a href="{{ $pnfs->url(1) }}" class="btn btn-outline-primary">
                        Ir a primera página
                    </a>
                @elseif ($pnfs->lastPage() > 1)
                    <a href="{{ $pnfs->url(1) }}" class="btn btn-outline-primary">
                        Ir a primera página
                    </a>
                    <a href="{{ $pnfs->url($pnfs->lastPage()) }}" class="btn btn-outline-primary">
                        Ir a última página
                    </a>
                @endif
            </div>
        </div>
        <div>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{-- Botón Anterior --}}
                    @if ($pnfs->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&laquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $pnfs->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif

                    {{-- Enlaces de páginas --}}
                    @foreach ($pnfs->getUrlRange(1, $pnfs->lastPage()) as $page => $url)
                        <li class="page-item {{ $pnfs->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Botón Siguiente --}}
                    @if ($pnfs->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $pnfs->nextPageUrl() }}" aria-label="Next">
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
        </div>
    

    <!-- Modal De modificar el pnf -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Datos del PNF</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="mb-3 text-center">
                                <label for="editPNF" class="form-label w-100" style="display: block; font-weight: bold;">PNF</label>
                                <input type="text" class="form-control mx-auto" id="editPNF" name="pnf" required style="width: 90%;" placeholder="Edita el nombre del PNF" maxlength="35">
                                <div class="invalid-feedback">
                                    El PNF no puede estar vacío.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-minimal btn-cancel" data-bs-dismiss="modal">
                        <img src="{{ asset('icons/close.svg') }}" alt="Icono de cancelar" class="icon-close">
                        Cancelar
                    </button>
                    <button class="btn-minimal btn-save" id="saveEdit">
                        <img src="{{ asset('icons/save_green.svg') }}" alt="Icono de guardar" class="icon-save">
                        Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de registro de PNF -->
    <div class="modal fade" id="registerStudentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registerStudentModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-lg" style="justify-content: center;">
            <div class="modal-content" style="width:80%; margin: auto;">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="registerStudentModalLabel">Registrar Nuevo PNF</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="mb-3 text-center">
                                <label for="regPNF" class="form-label w-100" style="display: block; font-weight: bold;">PNF</label>
                                <input type="text" class="form-control mx-auto" id="regPNF" name="regPNF" required style="width: 90%;" placeholder="Escribe el nombre del nuevo PNF" maxlength="35">
                                <div class="invalid-feedback">
                                    El PNF no puede estar vacío.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-minimal btn-cancel" data-bs-dismiss="modal">
                        <img src="{{ asset('icons/close.svg') }}" alt="Icono de cancelar" class="icon-close">
                        Cancelar
                    </button>
                    <button class="btn-minimal btn-save" id="registerStudent">
                        <img src="{{ asset('icons/save_green.svg') }}" alt="Icono de guardar" class="icon-save">
                        Registrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endsection
