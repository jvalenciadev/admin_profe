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

class EventoPreguntaController extends Controller
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
        $evePregunta = EventoPregunta::where('eve_pre_estado', '<>', 'eliminado')
                            ->where('eve_cue_id', $eveCuestionario[0]->eve_cue_id)
                            ->orderBy('updated_at', 'DESC')->get();
        $eveOpciones = EventoOpciones::where('eve_opc_estado', '<>', 'eliminado')
                            ->orderBy('updated_at', 'DESC')->get();
        $numeroDeCuestionario = $eveCuestionario->count();
        return view('backend.pages.evento.index-pregunta', compact('eveCuestionario','evePregunta','numeroDeCuestionario','evento'));
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
        $eventoCuestionario = EventoCuestionario::where('eve_cue_id', decrypt($id))->first();
        return view('backend.pages.evento.create-pregunta', compact('eventoCuestionario'));
    }

    /**
     * Store a newly created resource in storage.
     */
    ///  UTILIZADO
    public function store(Request $request)
    {
        // Verificar si el usuario tiene permisos
        if (is_null($this->user) || !$this->user->can('eventocuestionario.edit')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a crear eventos!');
        }

        // Validación de los campos del formulario
        $request->validate([
            'eve_pre_texto' => 'required|string|max:255',
            'eve_pre_tipo' => 'required|string', // Validar tipo de pregunta
            'eve_pre_obligatorio' => 'nullable|boolean', // Validar que el campo de obligación sea booleano
            'eve_cue_id' => 'required',
        ], [
            'eve_pre_texto.required' => 'El texto de la pregunta es obligatorio.',
            'eve_pre_tipo.required' => 'El tipo de pregunta es obligatorio.',
            'eve_pre_obligatorio.boolean' => 'El campo de obligación debe ser verdadero o falso.',
            'eve_cue_id.required' => 'El evento ID es obligatorio.',
        ]);
        // Añadir la regla de validación para la respuesta correcta solo si el tipo es 'respuesta_abierta'
        if ($request->eve_pre_tipo === 'respuesta_abierta') {
            $rules['eve_pre_respuesta_correcta'] = 'required|string|max:255';
        }
        // Crear el evento en la base de datos
        $eventoPregunta = new EventoPregunta(); // Asegúrate de que el modelo corresponda a tu estructura
        $eventoPregunta->eve_pre_texto = $request->eve_pre_texto;
        $eventoPregunta->eve_pre_tipo = $request->eve_pre_tipo; // Asigna el tipo de pregunta
        $eventoPregunta->eve_pre_respuesta_correcta = $request->eve_pre_respuesta_correcta??""; // Respuesta correcta
        $eventoPregunta->eve_pre_obligatorio = $request->eve_pre_obligatorio ? 1 : 0; // Almacena como booleano (1 o 0)
        $eventoPregunta->eve_cue_id = $request->eve_cue_id;

        // Guardar el evento en la base de datos
        $eventoPregunta->save();

        $eventoCuestionario = EventoCuestionario::find($request->eve_cue_id);
        return redirect()->route('admin.eventocuestionario.index', encrypt($eventoCuestionario->eve_id))
            ->with('success', 'Pregunta creada exitosamente.');
    }
    public function storeOpciones(Request $request)
    {
        // Verificar si el usuario tiene permisos
        if (is_null($this->user) || !$this->user->can('eventocuestionario.edit')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a crear eventos!');
        }

        // Validación de los campos del formulario
        $request->validate([
            'eve_opc_texto' => 'required|string|max:255',
            'eve_opc_es_correcta' => 'nullable|boolean', // Validar que el campo de obligación sea booleano
            'eve_pre_id' => 'required',
        ], [
            'eve_opc_texto.required' => 'El texto de la opción es obligatorio.',
            'eve_opc_es_correcta.boolean' => 'El campo de obligación debe ser verdadero o falso.',
            'eve_pre_id.required' => 'El evento ID es obligatorio.',
        ]);
        // Crear el evento en la base de datos
        $eventoOpcion = new EventoOpciones(); // Asegúrate de que el modelo corresponda a tu estructura
        $eventoOpcion->eve_opc_texto = $request->eve_opc_texto;
        $eventoOpcion->eve_opc_es_correcta = $request->eve_opc_es_correcta ? 1 : 0; // Almacena como booleano (1 o 0)
        $eventoOpcion->eve_pre_id = $request->eve_pre_id;

        // Guardar el evento en la base de datos
        $eventoOpcion->save();

        return back()->with('success', 'Actualizado');
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
    public function updateOpciones(Request $request, string $id)
    {
        // dd($request);
        if (is_null($this->user) || !$this->user->can('eventocuestionario.edit')) {
            abort(403, 'Lo siento!!, no esta autorizado para editar eventos');
        }
        // Validación de datos
        $request->validate([
            'eve_opc_texto' => 'required|string|max:255',
            'eve_opc_es_correcta' => 'nullable|boolean', // Validar que el campo de obligación sea booleano
        ], [
            'eve_opc_texto.required' => 'El texto de la opción es obligatorio.',
            'eve_opc_es_correcta.boolean' => 'El campo de obligación debe ser verdadero o falso.',
        ]);

        // Actualizar el evento
        $evento = EventoOpciones::find($id);
        $evento->eve_opc_texto = $request->eve_opc_texto;
        $evento->eve_opc_es_correcta = $request->eve_opc_es_correcta;

        $evento->save();

        return back()->with('success', 'Se a Actualizado');
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
    public function destroyOpciones(string $id)
    {
        $evento = EventoOpciones::where('eve_opc_id', $id)->first();

        if (!$evento) {
            abort(404, 'Evento no encontrado.');
        }
        $evento->eve_opc_estado = 'eliminado';
        $evento->save();

        return back()->with('success', 'Evento eliminado exitosamente.');
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
