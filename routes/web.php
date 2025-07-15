<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;


//controladores
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PnfController;

// Sección para las vistas estáticas de la página
Route::view('/', 'index')->name('index');
Route::view('/sobre_nosotros', 'aboutUs')->name('aboutUs');

// Cambia el nombre de esta ruta para evitar el conflicto
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show');

Route::get('/set-cookie', function() {
    return response('Cookie set')->cookie('test_cookie', '1', 10);
});

Route::get('/check-cookie', function(Request $request) {
    return response()->json([
        'cookie_present' => $request->cookie('test_cookie') !== null
    ]);
});


//Dashboard


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


#Seccionen donde se mostran las vistas de las secciones de la dashboard
//Maestros

//Metodos en la seccion de estudiantes
Route::prefix('/dashboard/estudiantes')->group(function () {
    // Ruta para mostrar los estudiantes en la tabla
    Route::get('/', [PersonaController::class, 'info'])->name('estudiantes');


    //Ruta para procesar el formulario y guardar el nuevo estudiante 
    Route::post('/',[PersonaController::class,'store'])->name('estudiantes.store');
    // Ruta para eliminar un estudiante por su cédula
    Route::delete('/{cedula}', [PersonaController::class, 'deleteEstudiante'])
        ->where('cedula', '[0-9]+')
        ->name('deleteEstudiante');
    // Ruta para mostrar el detalle de un estudiante por su cédula
    Route::get('/{cedula}/detalle', [PersonaController::class, 'detalleEstudiante'])
        ->where('cedula', '[0-9]+');
});


Route::view('/dashboard/administradores', 'dashboard.maestro.admin')->name('admin');
Route::view('/dashboard/becados', 'dashboard.maestro.becados')->name('becados');
Route::view('/dashboard/sede', 'dashboard.maestro.sede')->name('sede');
// REMOVIDA DUPLICIDAD: Route::view('/dashboard/pnf', 'dashboard.maestro.pnf')->name('pnf');
// Ya tienes una ruta GET para PNF con un controlador más abajo.

//Metodos en la seccion de pnf
Route::get('/dashboard/pnf', [PnfController::class,'info'])->name('pnf'); // Esta es la ruta principal de PNF
Route::post('/dashboard/pnf/agregar', [PnfController::class,'agregar']);
Route::delete('/dashboard/pnf/borrar/{id}', [PnfController::class,'borrar']);
Route::get('/dashboard/pnf/estatus/{id}', [PnfController::class,'estatus']);
Route::put('/dashboard/pnf/editar', [PnfController::class,'editar']);


Route::view('/dashboard/servicios', 'dashboard.maestro.servicio')->name('servicio');
Route::view('/dashboard/lapso', 'dashboard.maestro.lapso')->name('lapso');

//Movimientos
Route::view('/dashboard/registro_comedor', 'dashboard.movimientos.R_comedor')->name('R_comedor');
Route::view('/dashboard/solicitud_becas', 'dashboard.movimientos.S_becados')->name('S_becas');



Route::view('/dashboard/config', 'dashboard.config')->name('config');

//Servicios
Route::view('/dashboard/servicios/transporte', 'dashboard.servicios.transporte')->name('transporte');
Route::view('/dashboard/servicios/servicio_medico', 'dashboard.servicios.servicio_medico')->name('servicio_medico');
Route::view('/dashboard/servicios/comedor', 'dashboard.servicios.comedor')->name('comedor');
Route::view('/dashboard/servicios/atencion_social', 'dashboard.servicios.atencion_social')->name('atencion_social');
Route::view('/dashboard/servicios/censo', 'dashboard.reporte.censo')->name('censo');

// Esta ruta de POST login está correcta, solo necesita un nombre único o si es el principal, usar 'login'
Route::post('login', [LoginController::class, 'login'])->name('login'); // Esta es la que debes mantener con 'login'

Route::post('register', [RegisterController::class, 'register'])->name('register');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

Route::get('/verificar-cedula', function (Illuminate\Http\Request $request) {
    $exists = \App\Models\User::where('cedula', $request->cedula)->exists();
    return response()->json(['exists' => $exists]);
});

Route::get('/verificar-email', function (Illuminate\Http\Request $request) {
    $exists = \App\Models\User::where('email', $request->email)->exists();
    return response()->json(['exists' => $exists]);
});

Route::get('/session-debug', function(Request $request) {
    \Log::debug('Session ID: ' . $request->session()->getId());
    \Log::debug('Session Data: ' . print_r($request->session()->all(), true));
    
    return response()->json([
        'session_id' => $request->session()->getId(),
        'session_data' => $request->session()->all(),
        'auth_check' => Auth::check(),
        'user' => Auth::check() ? Auth::user() : null
    ]);
});

