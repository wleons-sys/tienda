<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Mostrar la página principal del portal
     * (Página de inicio con las 3 categorías y botón de ingresar)
     */
    public function index()
    {
        return view('portal.index');
    }
}