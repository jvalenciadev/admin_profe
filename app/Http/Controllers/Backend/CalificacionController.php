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

class CalificacionController extends Controller
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
        if (is_null($this->user) || !$this->user->can('calificacion.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún calificacion!');
        }

        $sede_id = $request->sede_id;
        
        $inscripciones = DB::table('programa_inscripcion')
            ->join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
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
                        'programa_turno.pro_tur_id', 
                        'programa_turno.pro_tur_nombre', 
                        'sede.sede_nombre',
                        'sede.sede_nombre_abre', 
                        'departamento.dep_nombre', 
                        'programa_inscripcion_estado.pie_nombre'
            );
        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) { // Verifica si $proIds no está vacío
                $inscripciones->whereIn('programa.pro_id', $proIds);
            }
        }
        $inscripciones = $inscripciones->get();
        $calificaciones = DB::table('calificacion_participante')
                ->join('programa_calificacion', 'calificacion_participante.pc_id', "=", "programa_calificacion.pc_id")
                ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
                ->get();
        $modulos = DB::table('programa_modulo')
            ->join('programa', 'programa.pro_id', "=", "programa_modulo.pro_id")
            ->join('programa_tipo', 'programa.pro_tip_id', "=", "programa_tipo.pro_tip_id")
            ->join('programa_calificacion', 'programa_tipo.pro_tip_id', "=", "programa_calificacion.pro_tip_id")
            ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
            ->select(
                    'programa_modulo.pm_id', 
                    'programa_modulo.pm_nombre', 
                    'programa_modulo.pro_id', 
                    'programa_calificacion.pc_id', 
                    'programa_tipo_calificacion.ptc_id', 
                    'programa_tipo_calificacion.ptc_nombre', 
                    'programa_tipo_calificacion.ptc_nota', 
            )->where('programa_modulo.pm_estado','activo')
            ->orderBy('programa_modulo.pm_nombre')
            ->orderBy('programa_tipo_calificacion.ptc_id')
            ->get();
        
        // Agrupar las inscripciones por pro_id
        // $baucheres= ProgramaBaucher::all();
        // Contar los baucheres por sede usando el sede_id
       
        //$mapPersona = MapPersona::paginate(100);
        return view('backend.pages.calificacion.index', compact(['inscripciones','sede_id', 'modulos', 'calificaciones']));
    }
    public function indexx(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('calificacion.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún calificacion!');
        }

        $sede_id = $request->sede_id;
        $pro_id = $request->pro_id;
        
        $inscripciones = DB::table('programa_inscripcion')
            ->join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_turno', 'programa_inscripcion.pro_tur_id', '=', 'programa_turno.pro_tur_id')
            ->join('sede', 'programa_inscripcion.sede_id', '=', 'sede.sede_id')
            ->join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
            ->join('programa_inscripcion_estado', 'programa_inscripcion.pie_id', '=', 'programa_inscripcion_estado.pie_id')
            ->where('sede.sede_id', decrypt($sede_id))
            ->where('programa.pro_id', decrypt($pro_id))
            ->select(
                        'programa_inscripcion.*', 
                        'map_persona.*', 
                        'programa.pro_id', 
                        'programa.pro_nombre', 
                        'programa.pro_nombre_abre', 
                        'programa_turno.pro_tur_id', 
                        'programa_turno.pro_tur_nombre', 
                        'sede.sede_nombre',
                        'sede.sede_nombre_abre', 
                        'departamento.dep_nombre', 
                        'programa_inscripcion_estado.pie_nombre'
            )->orderBy("map_persona.per_apellido1");
        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) { // Verifica si $proIds no está vacío
                $inscripciones->whereIn('programa.pro_id', $proIds);
            }
        }
        $inscripciones = $inscripciones->get();
        $calificaciones = DB::table('calificacion_participante')
                ->join('programa_calificacion', 'calificacion_participante.pc_id', "=", "programa_calificacion.pc_id")
                ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
                ->get();
        $modulos = DB::table('programa_modulo')
            ->join('programa', 'programa.pro_id', "=", "programa_modulo.pro_id")
            ->join('programa_tipo', 'programa.pro_tip_id', "=", "programa_tipo.pro_tip_id")
            ->join('programa_calificacion', 'programa_tipo.pro_tip_id', "=", "programa_calificacion.pro_tip_id")
            ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
            ->select(
                    'programa_modulo.pm_id', 
                    'programa_modulo.pm_nombre', 
                    'programa_modulo.pro_id', 
                    'programa_calificacion.pc_id', 
                    'programa_tipo_calificacion.ptc_id', 
                    'programa_tipo_calificacion.ptc_nombre', 
                    'programa_tipo_calificacion.ptc_nota', 
            )->where('programa_modulo.pm_estado','activo')
            ->orderBy('programa_modulo.pm_nombre')
            ->orderBy('programa_tipo_calificacion.ptc_id')
            ->get();
        
        // Agrupar las inscripciones por pro_id
        // $baucheres= ProgramaBaucher::all();
        // Contar los baucheres por sede usando el sede_id
       
        //$mapPersona = MapPersona::paginate(100);
        return view('backend.pages.calificacion.index', compact(['inscripciones','sede_id', 'modulos', 'calificaciones']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('inscripcion.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }
        // Decodificar el parámetro $sede_id
        $decryptedSedeId = decrypt($request->sede_id);

        // Realizar la consulta utilizando el método when para aplicar la condición si es necesario
        // $sede = Sede::where('sede_id', $decryptedSedeId)->first();
        $sede = Sede::join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
            ->where('sede.sede_id', $decryptedSedeId)
            ->select('sede.*', 'departamento.dep_nombre') // Selecciona los campos necesarios
            ->first();
        // Obtener todos los programas filtrados por pro_ids del usuario
        $programa = Programa::when($this->user->pro_ids, function ($query) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) { // Verifica si $sedesIds no está vacío
                $query->whereIn('pro_id', $proIds);
            }
        })->get();

        return view('backend.pages.inscripcion.create', compact('sede','programa'));
    }
    public function storeCalificacion(Request $request, $pi_id, $pm_id, $pc_id)
    {
            // Validación de los datos del formulario
        $request->validate([
            'cp_puntaje' => 'required|numeric|min:0|max:100', // Ejemplo de validación
        ], [
            'cp_puntaje.required' => 'El campo de puntaje es obligatorio.',
            'cp_puntaje.numeric'  => 'El puntaje debe ser un número.',
            'cp_puntaje.min'      => 'El puntaje no puede ser menor que 0.',
        ]);

        

        // Obtener el tipo de programa
        $programaTipo = DB::table('programa_modulo')
            ->join('programa', 'programa_modulo.pro_id', '=', 'programa.pro_id')
            ->join('programa_tipo', 'programa.pro_tip_id', '=', 'programa_tipo.pro_tip_id')
            ->where('programa_modulo.pm_id', $pm_id) 
            ->select('programa_tipo.pro_tip_id')
            ->first();
            
        if (!$programaTipo) {
            return redirect()->back()->with('error', 'Tipo de programa no encontrado.');
        }

        // Obtener pc_id para el programa total y la segunda instancia
        $programaTotal = DB::table('programa_calificacion')
            ->where('pro_tip_id', $programaTipo->pro_tip_id) 
            ->where('ptc_id', 4)
            ->select('pc_id')
            ->first();
        $programaSegundaInstancia = DB::table('programa_calificacion')
            ->where('pro_tip_id', $programaTipo->pro_tip_id) 
            ->where('ptc_id', 3)
            ->select('pc_id')
            ->first();
        if ($programaSegundaInstancia) {
            // Comprobar si la calificación de segunda instancia ya está en 70
            $calificacionSegundaInstancia = CalificacionParticipante::where([
                'pi_id' => $pi_id,
                'pm_id' => $pm_id,
                'pc_id' => $programaSegundaInstancia->pc_id
            ])->first();
    
            if ($calificacionSegundaInstancia && $calificacionSegundaInstancia->cp_puntaje == 70) {
                // Si la segunda instancia ya tiene 70 puntos, impedir la edición y guardar
                return redirect()->back()->with('error', 'La segunda instancia ya tiene 70 puntos y no se puede modificar.');
            }
        }
        // Obtener los pc_id para excluir
        $excludedPcIds = [];
        if ($programaTotal) {
            $excludedPcIds[] = $programaTotal->pc_id;
        }
        if ($programaSegundaInstancia) {
            $excludedPcIds[] = $programaSegundaInstancia->pc_id;
        }
        // Buscar la calificación existente o crear una nueva instancia
        $calificacion = CalificacionParticipante::firstOrNew([
            'pi_id' => $pi_id,
            'pm_id' => $pm_id,
            'pc_id' => $pc_id
        ]);
        // Asignar el puntaje del formulario
        $calificacion->pi_id = $pi_id;
        $calificacion->pm_id = $pm_id;
        $calificacion->pc_id = $pc_id;
        $calificacion->cp_puntaje = $request->cp_puntaje;

        // Guardar la calificación (ya sea actualización o nueva)
        $calificacion->save();
        // Calcular el total excluyendo los pc_id
        $total = DB::table('calificacion_participante')
            ->where('pi_id', $pi_id)
            ->where('pm_id', $pm_id)
            ->whereNotIn('pc_id', $excludedPcIds)
            ->sum('cp_puntaje');
        if ($programaSegundaInstancia && $calificacion->pc_id == $programaSegundaInstancia->pc_id) {
            // Si se asigna 70 puntos a la segunda instancia, actualizar el total con 70 puntos
            $total = 70;
        }
        if ($total > 100) {
            // Redirigir con mensaje de error si el total es mayor a 100
            return redirect()->back()->with('error', 'El total de puntos no puede ser mayor a 100. No se guardaron los cambios.');
        }
        if ($programaTotal) {
            // Actualizar o crear la calificación para el programa total
            $calificacionTotal = CalificacionParticipante::firstOrNew([
                'pi_id' => $pi_id,
                'pm_id' => $pm_id,
                'pc_id' => $programaTotal->pc_id,
            ]);
            $calificacionTotal->pi_id = $pi_id;
            $calificacionTotal->pm_id = $pm_id;
            $calificacionTotal->pc_id = $programaTotal->pc_id;
            $calificacionTotal->cp_puntaje = $total;
            // Determinar el estado basado en el puntaje total
            if ($total < 70) {
                $calificacionTotal->cp_estado = 'reprobado';
            } else {
                $calificacionTotal->cp_estado = 'aprobado';
            }
            $calificacionTotal->save();
        }
        // Redireccionar con mensaje de éxito
        return redirect()->back()->with('success', 'Calificación guardada correctamente.');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'per_rda' => 'required|numeric',
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'per_celular' => ['required', 'digits:8', 'regex:/^[67]\d{7}$/'],
            'sede_id' => 'required|exists:sede,sede_id',
            'pro_id' => 'required|exists:programa,pro_id',
            'pro_tur_id' => 'required',
        ], [
            'per_rda.required' => 'El campo RDA es obligatorio.',
            'per_rda.numeric' => 'El campo RDA debe ser numérico.',
            'per_id.exists' => 'El valor seleccionado para per_id no es válido.',
            'sede_id.required' => 'Debe seleccionar una sede válida.',
            'sede_id.exists' => 'La sede seleccionada no es válida.',
            'pro_tur_id.required' => 'Debe seleccionar un turno válido.',
            'pro_id.required' => 'Debe seleccionar un programa válido.',
            'pro_id.exists' => 'El programa seleccionado no es válido.',
            'per_celular.required' => 'El número de celular es obligatorio.',
            'per_celular.digits' => 'El número de celular debe tener exactamente 8 dígitos.',
            'per_celular.regex' => 'El número de celular debe comenzar con 6 o 7 y tener 8 dígitos en total.',
        ]);

        // Buscar la persona por per_rda para obtener el per_id
        $persona = MapPersona::where('per_rda', $request->per_rda)->first();

        // Verificar si la persona existe
        if (!$persona) {
            return redirect()->back()->with('error', 'La persona con RDA proporcionado no fue encontrada.');
        }

        if ($persona) {
            // Actualiza el celular si no es nulo en la solicitud
            if (!is_null($request->per_celular)) {
                $persona->per_celular = $request->per_celular;
            }

            // Actualiza el correo si no es nulo en la solicitud
            if (!is_null($request->per_correo)) {
                $persona->per_correo = $request->per_correo;
            }

            // Guarda los cambios en la base de datos
            $persona->save();
        }

        // Crear la inscripción
        $inscripcion = new ProgramaInscripcion();
        $inscripcion->per_id = $persona->per_id; // Asignar el per_id obtenido
        $inscripcion->pro_id = $request->pro_id;
        $inscripcion->pro_tur_id = $request->pro_tur_id;
        $inscripcion->sede_id = $request->sede_id;
        $inscripcion->pie_id = 1;
        // Añadir otros campos según tu estructura de datos

        // Guardar la inscripción
        $inscripcion->save();

        // Redireccionar con mensaje de éxito
        return redirect()->route('admin.inscripcion.index', ['sede_id' => encrypt($request->sede_id)])->with('success', 'La inscripción se ha creado correctamente.');
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
        if (is_null($this->user) || !$this->user->can('inscripcion.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }

        $pi_id = decrypt($pi_id);

        
        return view('backend.pages.inscripcion.edit', compact('inscripcion', 'programa', 'sede', 'bauchers','inscripcionestado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inscripcionId = decrypt($id); // Asegúrate de que $id sea el ID correcto de la inscripción

        // Validación de los datos del formulario
        $request->validate([
            'per_rda' => 'required|numeric',
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            #'per_celular' => ['required', 'digits:8', 'regex:/^[67]\d{7}$/'],
            #'sede_id' => 'required|exists:sede,sede_id',
            #'pro_id' => 'required|exists:programa,pro_id',
            'pi_doc_digital.*' => 'nullable|file|max:5120|mimes:pdf',
            'pro_tur_id' => 'required',
        ], [
            'per_rda.required' => 'El campo RDA es obligatorio.',
            'per_rda.numeric' => 'El campo RDA debe ser numérico.',
            'sede_id.required' => 'Debe seleccionar una sede válida.',
            'sede_id.exists' => 'La sede seleccionada no es válida.',
            #'pro_id.required' => 'Debe seleccionar un programa válido.',
            #'pro_id.exists' => 'El programa seleccionado no es válido.',
            #'per_celular.required' => 'El número de celular es obligatorio.',
            #'per_celular.digits' => 'El número de celular debe tener exactamente 8 dígitos.',
            #'per_celular.regex' => 'El número de celular debe comenzar con 6 o 7 y tener 8 dígitos en total.',
            'pro_tur_id.required' => 'Debe seleccionar un turno válido.',
            'pi_doc_digital.*.file' => 'El archivo adjunto debe ser un archivo.',
            'pi_doc_digital.*.max' => 'El archivo adjunto no debe superar los 5MB.',
            'pi_doc_digital.*.mimes' => 'El archivo adjunto debe ser de tipo PDF.',
        ]);

        // Buscar la persona por per_rda para obtener el per_id
        $persona = MapPersona::where('per_rda', $request->per_rda)->first();

        // Verificar si la persona existe
        if (!$persona) {
            return redirect()->back()->with('error', 'La persona con RDA proporcionado no fue encontrada.');
        }

        // Actualizar el celular si se proporcionó en la solicitud
        $persona->per_celular = $request->per_celular;
        $persona->per_correo = $request->per_correo;
        $persona->save();

        // Actualizar la inscripción
        $inscripcion = ProgramaInscripcion::findOrFail($inscripcionId);
        $inscripcion->sede_id = $request->sede_id;

        $inscripcion->pro_tur_id = $request->pro_tur_id;
        #$inscripcion->pro_id = $request->pro_id;
        #$inscripcion->pie_id = $request->pie_id;
        #$inscripcion->pi_modulo = $request->pi_modulo;

        if ($request->hasFile('pi_doc_digital')) {
            $documento = $request->file('pi_doc_digital');
            // Generar un nombre único basado en per_rda y la fecha actual
            $nombreDocumento = $persona->per_rda . '_' . now()->format('YmdHis') . '.' . $documento->getClientOriginalExtension();
            
            // Almacenar el documento en la carpeta 'pdfDocumentos' dentro del directorio 'storage/app/public'
            Storage::disk('public')->putFileAs('pdfDocumentos', $documento, $nombreDocumento);
            
            // Guardar el nombre del documento en la base de datos junto con la inscripción
            $inscripcion->pi_doc_digital = $nombreDocumento;
        }
        
        $inscripcion->save();

        // Redireccionar a una ruta adecuada después de editar la inscripción
        return redirect()->route('admin.inscripcion.index', ['sede_id' => encrypt($request->sede_id)])->with('success', 'La inscripción se ha actualizado correctamente.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function reporteCalificacionPdf($sede_id, $pro_id, $pm_id, $pro_tur_id)
    {
        
        // Desencriptar los IDs
        $sede_id = decrypt($sede_id);
        $pro_id = decrypt($pro_id);
        $pm_id = decrypt($pm_id);
        $pro_tur_id = decrypt($pro_tur_id);

        $inscritos = DB::table('programa_inscripcion as pi')
            ->join('map_persona as per', 'per.per_id', '=', 'pi.per_id')
            ->join('sede as sede', 'sede.sede_id', '=', 'pi.sede_id')
            ->join('programa as pro', 'pro.pro_id', '=', 'pi.pro_id')
            ->select(
                'pi.pi_id',
                'pi.pro_tur_id',
                'per.per_ci',
                DB::raw('CONCAT(
                    COALESCE(per.per_apellido1, ""), " ", 
                    COALESCE(per.per_apellido2, ""), ", ",
                    COALESCE(per.per_nombre1, ""), " ", 
                    COALESCE(per.per_nombre2, "")
                ) AS nombre_completo'),
                'sede.sede_nombre'
            )
            ->where('pi.pro_id', '=', $pro_id)
            ->where('pi.sede_id', '=', $sede_id)
            ->where('pi.pro_tur_id', '=', $pro_tur_id)
            ->orderBy('nombre_completo')
            ->get();

        $calificacion_participantes = DB::table('calificacion_participante as cp')
            ->join('programa_calificacion as pc', 'pc.pc_id', '=', 'cp.pc_id')
            ->select('cp.*', 'pc.ptc_id')
            ->where('cp.pm_id', '=', $pm_id)
            ->orderBy('cp.pi_id')
            ->orderBy('cp.cp_puntaje')
            ->get();

        $datos_programa = DB::table('programa as pro')
            ->join('programa_version as pv', 'pv.pv_id', '=', 'pro.pv_id')
            ->join('programa_modalidad as prom', 'prom.pm_id', '=', 'pro.pm_id')
            ->join('programa_tipo as pt', 'pt.pro_tip_id', '=', 'pro.pro_tip_id')
            ->join('programa_modulo as pm', 'pm.pro_id', '=', 'pro.pro_id')
            ->select(
                'pv.pv_gestion', 
                DB::raw('UPPER(pv.pv_nombre) as pv_nombre'),
                'pv.pv_numero',
                'pro.pro_nombre_abre',
                'pro.pro_id',
                DB::raw('UPPER(prom.pm_nombre) as pm_nombre'),
                DB::raw('UPPER(CONCAT(pt.pro_tip_nombre, ": ", pro.pro_nombre)) as programa_completo'),
                DB::raw('UPPER(CONCAT(pm.pm_nombre, " - ", pm.pm_descripcion)) as modulo_completo'),
                'pm.pm_fecha_inicio', 
                'pm.pm_fecha_fin'
            )
            ->where('pm.pm_id', '=', $pm_id)
            ->first();
        // Obtener responsable
        $sede_id = (string) $sede_id;
        $pro_id = (string) $pro_id;
        $responsable = DB::table('admins')
            ->select(
                'admins.nombre',
                'admins.apellidos',
                'admins.cargo',
                'admins.sede_ids',
                'roles.name'
            )
            ->join('model_has_roles', 'admins.id', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->whereJsonContains('admins.sede_ids', $sede_id)
            ->where('model_has_roles.role_id', '=', 2)
            ->first();

        // Obtener facilitador
        $facilitador = DB::table('admins')
            ->select(
                'admins.nombre',
                'admins.apellidos',
                'admins.cargo',
                'admins.sede_ids',
                'roles.name'
            )
            ->join('model_has_roles', 'admins.id', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->whereJsonContains('admins.sede_ids', $sede_id)
            ->whereJsonContains('admins.pro_ids', $pro_id)
            ->where('model_has_roles.role_id', '=', 3)
            ->first();
        // dd($facilitador);
        // Manejar la información del responsable
        if ($responsable) {
            $responsable_nombre = $responsable->nombre;
            $responsable_apellidos = $responsable->apellidos;
            $responsable_cargo = $responsable->name;
        } else {
            $responsable_nombre = 'No encontrado';
            $responsable_apellidos = 'No encontrado';
            $responsable_cargo = 'No encontrado';
        }

        // Manejar la información del facilitador
        if ($facilitador) {
            $facilitador_nombre = $facilitador->nombre;
            $facilitador_apellidos = $facilitador->apellidos;
            $facilitador_cargo = $facilitador->name;
        } else {
            if ($pro_id == 9){
                $facilitador_nombre = 'ROXANA';
                $facilitador_apellidos = 'ARAUJO ALIAGA';
                $facilitador_cargo = 'FACILITADOR';
            }else{
                $facilitador_nombre = 'No encontrado';
                $facilitador_apellidos = 'No encontrado';
                $facilitador_cargo = 'No encontrado';
            }
        }
         
        //
        $imagen1 = public_path() . "/img/logos/logominedu.jpg";
        $logo1 = base64_encode(file_get_contents($imagen1));

        $imagen2 = public_path() . "/img/logos/logoprofe.jpg";
        $logo2 = base64_encode(file_get_contents($imagen2));

        $imagen3 = public_path() . "/img/iconos/alerta.png";
        $logo3 = base64_encode(file_get_contents($imagen3));

        $imagen4 = public_path() . "/img/logos/fondo.jpg";
        $fondo = base64_encode(file_get_contents($imagen4));
        $fechaActual = date('d \d\e M Y h:i a');
        // Array para traducir los meses de inglés a español
        $mesesIngles = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $mesesEspanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        // Reemplazar el nombre del mes en inglés por español
        $fechaActual = str_replace($mesesIngles, $mesesEspanol, $fechaActual);
        $totalMatriculados = $inscritos->count();
        $datosQr = 'FAC: '.$facilitador_nombre.' '.$facilitador_apellidos.' |S:'.$sede_id.' |P:'.$pro_id.' |G:'.$pro_tur_id.' |M:'.$pm_id.' |MATRICULADOS:'.$totalMatriculados.' |F: '.$fechaActual;
        $qr = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($datosQr));
        $pdf = PDF::loadView('backend.pages.calificacion.partials.reporteCalificacionPdf', compact('inscritos', 'calificacion_participantes', 'datos_programa' , 'responsable_nombre', 'responsable_apellidos', 'responsable_cargo',
        'facilitador_nombre', 'facilitador_apellidos', 'facilitador_cargo','logo1', 'qr', 'logo2', 'logo3', 'fondo'));
        // VERTICAL
        $pdf->setPaper('Letter', 'portrait');
        // HORIZONTAL
        // $pdf->setPaper('Letter', 'landscape');
        //
        return $pdf->stream('Calificacion' . $datos_programa->pro_nombre_abre . '.pdf');
        // return $pdf->download('mi-archivo.pdf');
    }
}
