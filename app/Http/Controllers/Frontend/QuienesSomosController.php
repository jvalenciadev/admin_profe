<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sede;
use App\Models\Profe;

class QuienesSomosController extends Controller
{
    // Programas
    public function index()
    {
        $profe = Profe::first();
        $sedes = Sede::join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
                ->where('sede_estado', 'activo')->orderBy('sede_id', 'DESC')->get();

        return view('frontend.pages.quienesomos.index', compact('sedes','profe'));
    }


}
