<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sede;
use App\Models\Galeria;

class SedeController extends Controller
{
    // Programas
    public function index()
    {
        $sedes = Sede::join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
                ->where('sede_estado', 'activo')->orderBy('sede_id', 'DESC')->get();

        return view('frontend.pages.sedes.index', compact('sedes'));
    }

    public function show($sede_id)
    {
        // Obtener los detalles de la sede con su departamento
        $sede = Sede::join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
            ->select('sede.*', 'departamento.dep_nombre')
            ->where('sede_estado', 'activo')
            ->where('sede_id', $sede_id)
            ->first();

        // Validar si la sede existe
        if (!$sede) {
            abort(404, 'Sede no encontrada');
        }

        // Obtener las galerías agrupadas por programa
        $galeriasPorPrograma = Galeria::selectRaw(
                'galeria.*,
                programa.pro_nombre_abre,
                sede.sede_nombre_abre'
            )
            ->join('sede', 'galeria.sede_id', '=', 'sede.sede_id')
            ->join('programa', 'galeria.pro_id', '=', 'programa.pro_id')
            ->where('galeria.galeria_estado', 'activo')
            ->where('sede.sede_id', $sede_id)
            ->orderBy('galeria.updated_at', 'desc')
            ->get()
            ->groupBy('pro_id'); // Agrupar por programa

        return view('frontend.pages.sedes.sede', compact('sede', 'galeriasPorPrograma'));
    }

}
