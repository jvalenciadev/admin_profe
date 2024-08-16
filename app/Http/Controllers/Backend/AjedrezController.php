<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Añade esta línea para importar la clase Str
use App\Models\Programa;
use App\Models\Sede;
use App\Models\MapPersona;
use App\Models\ProgramaBaucher;
use App\Models\ProgramaInscripcion;
use App\Models\ParticipantesAjedrez;
use App\Models\ProgramaModalidad;
use App\Models\ProgramaInscripcionEstado;
use App\Models\ProgramaVersion;
use App\Models\CalificacionParticipante;
use App\Models\ProgramaRestriccion;
use App\Models\ProgramaTipo;
use App\Imports\DepartamentoImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class AjedrezController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('ajedrez.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún calificacion!');
        }

        $sede_id = $request->sede_id;
        
        $inscripciones = DB::table('programa_inscripcion')
            ->join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->leftJoin('participantes_ajedrez', 'programa_inscripcion.pi_id', '=', 'participantes_ajedrez.pi_id')
            ->join('programa_turno', 'programa_inscripcion.pro_tur_id', '=', 'programa_turno.pro_tur_id')
            ->join('sede', 'programa_inscripcion.sede_id', '=', 'sede.sede_id')
            ->join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
            ->join('programa_inscripcion_estado', 'programa_inscripcion.pie_id', '=', 'programa_inscripcion_estado.pie_id')
            ->where('sede.sede_id', decrypt($sede_id))
            ->select(
                        'programa_inscripcion.*', 
                        'map_persona.*', 
                        'programa.pro_id', 
                        'programa.pro_nombre', 
                        'programa.pro_nombre_abre', 
                        'programa.pro_costo', 
                        'participantes_ajedrez.pa_estado', 
                        'participantes_ajedrez.updated_at', 
                        'programa_turno.pro_tur_nombre', 
                        'sede.sede_nombre',
                        'sede.sede_nombre_abre', 
                        'departamento.dep_nombre', 
                        'programa_inscripcion_estado.pie_nombre'
            )->orderByRaw("CASE WHEN participantes_ajedrez.pa_estado = 'activo' THEN 1 ELSE 2 END")
            ->orderBy('participantes_ajedrez.updated_at', 'desc');
        $participantes = DB::table('participantes_ajedrez')
            ->select('participantes_ajedrez.*')
            ->get(); 
        $inscripciones->where('programa.pro_id', 8);
        $inscripciones = $inscripciones->get();
        return view('backend.pages.ajedrez.index', compact(['inscripciones','sede_id','participantes']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('ajedrez.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }
        return view('backend.pages.ajedrez.create', compact('sede','programa'));
    }
    public function storeAjedrez(Request $request, $pi_id)
    {
        if (is_null($this->user) || !$this->user->can('ajedrez.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }
        $request->validate([
            'pa_estado' => 'required|in:activo,inactivo'
        ]);

        $participantes = ParticipantesAjedrez::firstOrNew([
            'pi_id' => $pi_id,
        ]);
        // Asegúrate de que pc_id esté asignado
        $participantes->pi_id = $pi_id;
        // Asignar el puntaje del formulario
        $participantes->pa_estado = $request->pa_estado;

        // Guardar la calificación (ya sea actualización o nueva)
        $participantes->save();

        // Redireccionar con mensaje de éxito
        return redirect()->back()->with('success', 'Campeonato ajedrez actualizado');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.ajedrez.index', ['sede_id' => encrypt($request->sede_id)])->with('success', 'La inscripción se ha creado correctamente.');
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
    public function edit(string $pi_id)
    {
        if (is_null($this->user) || !$this->user->can('ajedrez.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }

       
        return view('backend.pages.ajedrez.edit', compact('inscripcion', 'programa', 'sede', 'bauchers','inscripcionestado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
        return redirect()->route('admin.ajedrez.index', ['sede_id' => encrypt($request->sede_id)])->with('success', 'La inscripción se ha actualizado correctamente.');
    }
    
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
