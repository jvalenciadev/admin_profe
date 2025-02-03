<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Galeria;
use App\Models\Sede;
use App\Models\Programa;
use Illuminate\Http\Request;

class GaleriaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener las galerías activas agrupadas por programa
        $galeriasPorPrograma = Galeria::select('galeria.*', 'sede.sede_nombre_abre','departamento.dep_abreviacion','galeria.pro_id', 'programa.pro_nombre_abre')
            ->join('sede', 'galeria.sede_id', '=', 'sede.sede_id')
            ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
            ->join('programa', 'galeria.pro_id', '=', 'programa.pro_id')
            ->where('galeria_estado', 'activo')
            ->orderBy('galeria.updated_at', 'desc')
            ->get()
            ->groupBy(['pro_id']) // Agrupar por programa y sede
            ->map(function ($galerias) {
                return $galerias->take(2); // Tomar solo las primeras 2 imágenes
            }); // Agrupar por programa

        return view('frontend.pages.galeria.index', compact('galeriasPorPrograma'));
    }


}
