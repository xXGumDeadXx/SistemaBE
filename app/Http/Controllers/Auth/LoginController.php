<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// Quita la línea 'use App\Models\User;' de aquí si ya no la necesitas,
// ya que Auth::attempt() no la usa directamente.
// use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, ThrottlesLogins;


    protected $redirectTo = '/dashboard';


    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Lógica de login
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Registra los datos de la cédula que se intentan loguear.
        Log::info('Intento de login con cédula: ' . $request->cedula);

        // Si las credenciales son correctas
        if ($this->attemptLogin($request)) { 

            // Registra que Auth::attempt fue exitoso.
            Log::info('Auth::attempt ha devuelto TRUE.');

            // Obtener el usuario autenticado 
            $user = Auth::user();
            Log::info('Auth::check() es TRUE. Usuario ID: ' . ($user->id_usuario ?? 'ID_USUARIO NO ENCONTRADO O NULL') . ' Tipo de ID: ' . gettype($user->id_usuario));
            Log::info('Nombre de persona asociado: ' . ($user->persona->nombre_persona ?? 'NO ASOCIADO O NULL'));


            // --- DEPURACIÓN DE SESIÓN ---
            Log::info('ID de sesión ANTES de regenerar: ' . session()->getId());
            $request->session()->regenerate();
            Log::info('ID de sesión DESPUÉS de regenerar: ' . session()->getId());
            Log::info('Contenido de la sesión DESPUÉS de regenerar: ' . json_encode(session()->all()));
            // --- FIN DE DEPURACIÓN ---

            $this->clearLoginAttempts($request); // Limpia los intentos de login

            // Redirige al usuario a la URL prevista o al dashboard.
            return $this->sendLoginResponse($request); 


        } else {
            // Manejo de intento fallido
            Log::warning('Auth::attempt ha devuelto FALSE. Las credenciales son incorrectas.');
            $this->incrementLoginAttempts($request);

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request); // Usa el método del trait para bloqueo
            }

            return $this->sendFailedLoginResponse($request); // Usa el método del trait para respuesta fallida
        }
    }


    // Validación de campos 
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    // Límite de intentos 
    protected function maxAttempts()
    {
        return 3;
    }

    // Tiempo de espera en minutos 
    protected function decayMinutes()
    {
        return 3;
    }

    // Nombre del campo de usuario 
    public function username()
    {
        return 'cedula';
    }

    // Clave para contar intentos por IP y usuario 
    protected function throttleKey(Request $request) // Asegúrate que recibe Request
    {
        return strtolower($request->input($this->username())) . '|' . $request->ip();
    }
}