<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\EventoCuestionario;
use App\Models\EventoPregunta;
use App\Models\EventoOpciones;
use App\Models\ProgramaModalidad;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventoCuestionarioController extends Controller
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
     *///  UTILIZADO
    public function index(string $id)
    {
        if (is_null($this->user) || !$this->user->can('eventocuestionario.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún evento!');
        }
        $evento = Evento::where('eve_id', decrypt($id))->first();
        $eveCuestionario = EventoCuestionario::where('eve_cue_estado', '<>', 'eliminado')
                            ->where('eve_id', decrypt($id))
                            ->orderBy('updated_at', 'DESC')
                            ->get();
        $evePregunta = EventoPregunta::where('eve_pre_estado', '<>', 'eliminado')->orderBy('updated_at', 'DESC')->get();
        $eveOpciones = EventoOpciones::where('eve_opc_estado', '<>', 'eliminado')->orderBy('updated_at', 'DESC')->get();
        $numeroDeCuestionario = $eveCuestionario->count();
        return view('backend.pages.evento.index-cuestionario', compact('eveCuestionario','evePregunta','numeroDeCuestionario','evento', 'eveOpciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    ///  UTILIZADO
    public function create(string $id)
    {
        if (is_null($this->user) || !$this->user->can('eventocuestionario.edit')) {
            abort(403, 'Lo siento !! ¡No estas autorizado a crear eventos !');
        }
        $evento = Evento::where('eve_id', decrypt($id))->first();
        return view('backend.pages.evento.create-cuestionario', compact('evento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    ///  UTILIZADO
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('eventocuestionario.edit')) {
            abort(403, 'Lo siento !! ¡No estas autorizado a crear eventos !');
        }
        $request->validate([
            'eve_cue_titulo' => 'required|string|max:255',
            'eve_cue_descripcion' => 'required|string',
            'eve_cue_fecha_ini' => 'required',
            'eve_cue_fecha_fin' => 'required',
            'eve_id' => 'required',
        ], [
            'eve_cue_titulo.required' => 'El nombre del cuestionario es obligatorio.',
            'eve_cue_descripcion.required' => 'La descripción del cuestionario es obligatoria.',
            'eve_cue_fecha_ini.required' => 'La hora de asistencia habilitado es obligatoria.',
            'eve_cue_fecha_fin.required' => 'La hora de asistencia deshabilitado es obligatoria.',
        ]);

        // Crear el evento en la base de datos
        $eventoCuestionario = new EventoCuestionario();
        $eventoCuestionario->eve_cue_titulo = $request->eve_cue_titulo;
        $eventoCuestionario->eve_cue_descripcion = $request->eve_cue_descripcion;
        $eventoCuestionario->eve_cue_fecha_ini = $request->eve_cue_fecha_ini;
        $eventoCuestionario->eve_cue_fecha_fin = $request->eve_cue_fecha_fin;
        $eventoCuestionario->eve_id = $request->eve_id;

        // Guardar el evento en la base de datos
        $eventoCuestionario->save();
        return redirect()->route('admin.eventocuestionario.index', encrypt($request->eve_id))
            ->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        return view('backend.pages.evento.index-cuestionario');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        if (is_null($this->user) || !$this->user->can('eventocuestionario.edit')) {
            abort(403, 'Lo siento!!, no esta autorizado para editar eventos');
        }
        $cuestionario = EventoCuestionario::where('eve_id', decrypt($id))->first();

        if (!$cuestionario) {
            abort(404, 'Cuestionario no encontrado.');
        }

        return view('backend.pages.evento.edit-cuestionario', compact('cuestionario'));
    }

    public function update(Request $request, string $id)
    {
        // dd($request);
        if (is_null($this->user) || !$this->user->can('eventocuestionario.edit')) {
            abort(403, 'Lo siento!!, no esta autorizado para editar eventos');
        }
        // Validación de datos
        $request->validate([
            'eve_cue_titulo' => 'required|string|max:255',
            'eve_cue_descripcion' => 'required|string',
            'eve_cue_fecha_ini' => 'required',
            'eve_cue_fecha_fin' => 'required',
            'eve_id' => 'required',
        ], [
            'eve_cue_titulo.required' => 'El nombre del cuestionario es obligatorio.',
            'eve_cue_descripcion.required' => 'La descripción del cuestionario es obligatoria.',
            'eve_cue_fecha_ini.required' => 'La hora de asistencia habilitado es obligatoria.',
            'eve_cue_fecha_fin.required' => 'La hora de asistencia deshabilitado es obligatoria.',
        ]);

        // Actualizar el evento
        $evento = EventoCuestionario::find(decrypt($id));
        $evento->eve_cue_titulo = $request->eve_cue_titulo;
        $evento->eve_cue_descripcion = $request->eve_cue_descripcion;
        $evento->eve_cue_fecha_ini = $request->eve_cue_fecha_ini;
        $evento->eve_cue_fecha_fin = $request->eve_cue_fecha_fin;
        $evento->eve_id = $request->eve_id;

        $evento->save();

        return redirect()->route('admin.eventocuestionario.index', encrypt($request->eve_id))
            ->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evento = Evento::where('eve_id', $id)->first();

        if (!$evento) {
            abort(404, 'Evento no encontrado.');
        }
        $evento->eve_estado = 'eliminado';
        $evento->save();

        return redirect()->route('admin.evento.index')
            ->with('success', 'Evento eliminado exitosamente.');
    }

    public function estado(string $id)
    {
        $evento = Evento::where('eve_id', $id)->first();
        if ($evento->eve_estado == 'activo') {
            $evento->eve_estado = 'inactivo';
        } else {
            $evento->eve_estado = 'activo';
        }
        $evento->save();

        return back()->with('success', 'Estado actualizado');
    }
}
