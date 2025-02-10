<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SolicitudInscripcionSede;
use App\Models\Programa;
use App\Models\Sede;


class SolicitudesController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('inscripcion.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ninguna imagen!');
        }

        $sis = SolicitudInscripcionSede::
                join('programa', 'programa.pro_id', '=','solicitud_inscripcion_sede.pro_id')
                ->where('solicitud_inscripcion_sede.sis_estado', '<>', 'eliminado')
                ->select('programa.pro_nombre_abre', 'solicitud_inscripcion_sede.updated_at',
                'solicitud_inscripcion_sede.sis_nombre_completo','solicitud_inscripcion_sede.sis_ci',
                'solicitud_inscripcion_sede.sis_celular','solicitud_inscripcion_sede.sis_sede',
                'solicitud_inscripcion_sede.sis_departamento', 'solicitud_inscripcion_sede.sis_turno',
                'solicitud_inscripcion_sede.sis_estado')
                    ->orderBy('solicitud_inscripcion_sede.updated_at');
        $sis = $sis->get();
        return view('backend.pages.solicitudes.index', compact('sis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $galeria_id)
    {

    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }



    public function estado(Request $request, string $id)
    {
        // if (is_null($this->user) || !$this->user->can('solicitudes.estado')) {
        //     return response()->json(['error' => 'No autorizado'], 403);
        // }

        $sis = SolicitudInscripcionSede::where('sis_id', $id)->first();

        if (!$sis) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        // Validar el estado recibido
        $nuevoEstado = $request->input('sis_estado');
        if (!in_array($nuevoEstado, ['no confirmado', 'confirmado', 'eliminado'])) {
            return response()->json(['error' => 'Estado no válido'], 400);
        }

        $sis->sis_estado = $nuevoEstado;
        $sis->save();

        return response()->json(['success' => true, 'message' => 'Estado actualizado']);
    }


}
