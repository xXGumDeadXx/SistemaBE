
<nav class="nav-main navbar">
    <div class="d-flex container-fluid ">
        <div class="d-flex aling-items-center">
            <!--
            <div class="container-imagen">
                <img src="{{ asset('img/imagen-gotty.png') }}" alt="Logo de la universidad" class="header-logo" style="width: 65px;margin-right: 10px;">
            </div>
        -->
            <p class="title">Control Administrativo del <br>Departamento del Bienestras Estudiantil</p>
            <a href="{{ route('dashboard') }}" class="navbar-brand ms-5 nav-link">Panel de control/</a>
            @yield('links')
        </div>  
        <div id="hora">
    <span class="navbar-text" id="spanHora" style="color: white"></span><br>

    <strong>
        @auth {{-- Verifica si hay un usuario autenticado --}}
        Bienvenido, {{ Auth::user()?->persona?->nombre_persona ?? 'Invitado' }}
        @endauth
    </strong>
</div>
    </div>
</nav>
<!-- Secondary nav -->
<nav class="nav-secondary navbar navbar-expand-lg">
    <div class="container-fluid justify-content-center">
        <ul class="navbar-nav mb-2 mb-lg-0 d-flex justify-content-center">
            <!-- Maestros Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="maestrosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('icons/person.svg') }}" alt="Icono de usuario" class="nav-icon me-1">
                    Maestros
                </a>
                <ul class="dropdown-menu custom-dropdown-bg" aria-labelledby="maestrosDropdown">
                    <li><a class="dropdown-item" href="{{ route('estudiantes') }}">Estudiantes</a></li>
                    <li><a class="dropdown-item" href="{{ route('pnf') }}">PNF</a></li>
                    <li><a class="dropdown-item" href="{{ route('sede') }}">Sede/Extensiones</a></li>
                    <li><a class="dropdown-item" href="{{ route('servicio') }}">Servicios</a></li>
                    <li><a class="dropdown-item" href="{{ route('lapso') }}">Lapso Academico</a></li>
                </ul>
            </li>
            <!-- Movimientos Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="movimientosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('icons/trending_up.svg') }}" alt="Icono de movimientos" class="nav-icon me-1">
                    Movimientos
                </a>
                <ul class="dropdown-menu custom-dropdown-bg" aria-labelledby="movimientosDropdown">
                    <li><a class="dropdown-item" href="{{ route('R_comedor') }}">Registro Comedor</a></li>
                    <li><a class="dropdown-item" href="{{ route('S_becas') }}">Solicitud de becas</a></li>
                </ul>
            </li>
            <!-- Reportes Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="reportesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('icons/content_search.svg') }}" alt="Icono de reportes" class="nav-icon me-1">
                    Reportes/Consultas
                </a>
                <ul class="dropdown-menu custom-dropdown-bg" aria-labelledby="reportesDropdown">
                    <li><a class="dropdown-item" href="{{ route('censo') }}">Comedor</a></li>
                    <li><a class="dropdown-item" href="#">Becas</a></li>
                </ul>
            </li>
            <!-- Configuracion -->
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="{{ route('config') }}">
                    <img src="{{ asset('icons/settings.svg') }}" alt="Icono de configuracion" class="nav-icon me-1">
                    Configuración
                </a>
            </li>
            <!-- Cerrar Sesion -->
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                    <img src="{{ asset('icons/logout.svg') }}" alt="Icono de logout" class="nav-icon me-1">
                    Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</nav>
<style>
    .custom-dropdown-bg {
        background-color: #f1f1f1 !important;
    }
    .container-imagen{
        Filter: brightness(0) invert(100%);
    }
    .nav-main{
        width: calc(100% - var(--size-bar-lateral));
        height: 80px;
        background-color: var(--prymary-color);
        position: fixed;
        top: 0;
        margin-left: var(--size-bar-lateral);   
        transition: .4s ease;
        box-shadow: 0px 5px 15px -2px rgba(0, 0, 0, 0.3);
        z-index: 1000;
    }

    .nav-secondary{
        display: flex;
        flex-direction: row;
        justify-content: center;
        width: calc(100% - var(--size-bar-lateral));
        margin-left: var(--size-bar-lateral);
        background-color: #f8f9fa; 
        border-bottom: 1px solid #e0e0e0; 
        position: fixed; 
        top: var(--height-header); 
        z-index: 999;
        transition: margin-left .4s ease;
    }
    .nav-secondary .nav-icon{
        filter: brightness(.8)
    }

    .nav-secondary .navbar-nav {
        justify-content: center !important;
    }

    .nav-secondary.collapsed{
        margin-left: var(--size-bar-lateral-collapsed);
        width: calc(100% - var(--size-bar-lateral-collapsed));
    }

    .nav-main.colapsed{
        width: calc(100% - var(--size-bar-lateral-collapsed));
    }

    .nav-main.colapsed{
        margin-left: var(--size-bar-lateral-collapsed);
    }
    #hora{
        color: white;
        z-index: 1000;
        text-align: right
    }
    .title{
        text-align: center;
        color: white;
        margin: 0
    }
    .nav-main > div .nav-link{
        color: rgb(245, 245, 245);
        &:hover{
            text-decoration: underline;
        }
    }

</style>