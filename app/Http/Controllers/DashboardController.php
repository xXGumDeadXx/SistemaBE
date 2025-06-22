<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        //dd(Auth::user());

            \Log::info('Valor de sesión prueba_sesion: ' . session('prueba_sesion'));


        return view('dashboard.index'); // carga resources/views/dashboard/index.blade.php
    }
}



