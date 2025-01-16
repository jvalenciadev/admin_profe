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
use Milon\Barcode\DNS1D;

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

        $sede_id = decrypt($request->sede_id);
        $pro_id = decrypt($request->pro_id);
        
        $inscripciones = DB::table('programa_inscripcion')
            ->join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_turno', 'programa_inscripcion.pro_tur_id', '=', 'programa_turno.pro_tur_id')
            ->join('sede', 'programa_inscripcion.sede_id', '=', 'sede.sede_id')
            ->join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
            ->join('programa_inscripcion_estado', 'programa_inscripcion.pie_id', '=', 'programa_inscripcion_estado.pie_id')
            ->where('sede.sede_id', $sede_id)
            ->where('programa.pro_id', $pro_id)
            ->where('programa_inscripcion.pi_estado', "=",'activo')
            ->where('programa_inscripcion.pie_id', "=",2)
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
            )->orderBy("map_persona.per_apellido1")->orderBy("map_persona.per_apellido2");
        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) { // Verifica si $proIds no está vacío
                $inscripciones->whereIn('programa.pro_id', $proIds);
            }
        }
        $facilitador = DB::table('admins')
            ->select(
                'admins.nombre',
                'admins.apellidos',
                'admins.celular',
                'admins.cargo',
                'admins.sede_ids',
                'roles.name'
            )
            ->join('model_has_roles', 'admins.id', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->whereJsonContains('admins.sede_ids', (string) $sede_id)
            ->whereJsonContains('admins.pro_ids', (string) $pro_id)
            ->where('model_has_roles.role_id', '=', 3)
            ->first();
        // dd($facilitador);
        

        // Manejar la información del facilitador
        if ($facilitador) {
            $facilitador_nombre = $facilitador->nombre;
            $facilitador_apellidos = $facilitador->apellidos;
            $facilitador_celular = $facilitador->celular;
        } else {
            if ($pro_id == 9){
                $facilitador_nombre = 'ROXANA';
                $facilitador_apellidos = 'ARAUJO ALIAGA';
                $facilitador_celular = '77216668';
            }else{
                $facilitador_nombre = 'No encontrado';
                $facilitador_apellidos = 'No encontrado';
                $facilitador_celular = 'No encontrado';
            }
        }
        $inscripciones = $inscripciones->get();
        // $calificaciones = DB::table('calificacion_participante')
        //         ->join('programa_calificacion', 'calificacion_participante.pc_id', "=", "programa_calificacion.pc_id")
        //         ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
        //         ->get();
        $modulos = DB::table('programa_modulo')
            ->join('programa', 'programa.pro_id', "=", "programa_modulo.pro_id")
            ->join('programa_tipo', 'programa.pro_tip_id', "=", "programa_tipo.pro_tip_id")
            ->join('programa_calificacion', 'programa_tipo.pro_tip_id', "=", "programa_calificacion.pro_tip_id")
            ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
            ->select(
                    'programa_modulo.pm_id', 
                    'programa_modulo.pm_nombre', 
                    'programa_modulo.pro_id', 
                    'programa_modulo.pm_estado', 
                    'programa_calificacion.pc_id', 
                    'programa_tipo_calificacion.ptc_id', 
                    'programa_tipo_calificacion.ptc_nombre', 
                    'programa_tipo_calificacion.ptc_nota', 
            )
            ->whereIn('programa_modulo.pm_estado', ['activo', 'vista'])
            ->where('programa.pro_id', $pro_id)
            ->orderBy('programa_modulo.pm_nombre')
            ->orderBy('programa_tipo_calificacion.ptc_id')
            ->get();
        
        
        // Agrupar las inscripciones por pro_id
        // $baucheres= ProgramaBaucher::all();
        // Contar los baucheres por sede usando el sede_id
        $sede_id = encrypt($sede_id);        
        //$mapPersona = MapPersona::paginate(100);
        return view('backend.pages.calificacion.index', compact(['inscripciones','sede_id', 
        'modulos', 'facilitador_nombre','facilitador_apellidos','facilitador_celular']));
    }
    public function investigacion(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('calificacion.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún calificacion!');
        }

        $sede_id = decrypt($request->sede_id);
        $pro_id = decrypt($request->pro_id);
        
        $inscripciones = DB::table('programa_inscripcion')
            ->join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_turno', 'programa_inscripcion.pro_tur_id', '=', 'programa_turno.pro_tur_id')
            ->join('sede', 'programa_inscripcion.sede_id', '=', 'sede.sede_id')
            ->join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
            ->join('programa_inscripcion_estado', 'programa_inscripcion.pie_id', '=', 'programa_inscripcion_estado.pie_id')
            ->where('sede.sede_id', $sede_id)
            ->where('programa.pro_id', $pro_id)
            ->where('programa_inscripcion.pi_estado', "=",'activo')
            ->where('programa_inscripcion.pie_id', "=",2)
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
            )->orderBy("map_persona.per_apellido1")->orderBy("map_persona.per_apellido2");
        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) { // Verifica si $proIds no está vacío
                $inscripciones->whereIn('programa.pro_id', $proIds);
            }
        }
        $facilitador = DB::table('admins')
            ->select(
                'admins.nombre',
                'admins.apellidos',
                'admins.celular',
                'admins.cargo',
                'admins.sede_ids',
                'roles.name'
            )
            ->join('model_has_roles', 'admins.id', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->whereJsonContains('admins.sede_ids', (string) $sede_id)
            ->whereJsonContains('admins.pro_ids', (string) $pro_id)
            ->where('model_has_roles.role_id', '=', 3)
            ->first();
        // dd($facilitador);
        

        // Manejar la información del facilitador
        if ($facilitador) {
            $facilitador_nombre = $facilitador->nombre;
            $facilitador_apellidos = $facilitador->apellidos;
            $facilitador_celular = $facilitador->celular;
        } else {
            if ($pro_id == 9){
                $facilitador_nombre = 'ROXANA';
                $facilitador_apellidos = 'ARAUJO ALIAGA';
                $facilitador_celular = '77216668';
            }else{
                $facilitador_nombre = 'No encontrado';
                $facilitador_apellidos = 'No encontrado';
                $facilitador_celular = 'No encontrado';
            }
        }
        $inscripciones = $inscripciones->get();
        // $calificaciones = DB::table('calificacion_participante')
        //         ->join('programa_calificacion', 'calificacion_participante.pc_id', "=", "programa_calificacion.pc_id")
        //         ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
        //         ->get();
        $programa = DB::table('programa')
                    ->select('programa.*') 
                    ->where('pro_id', '=',$pro_id)->first();
        $modulos = DB::table('programa_modulo')
            ->join('programa', 'programa.pro_id', "=", "programa_modulo.pro_id")
            ->join('programa_tipo', 'programa.pro_tip_id', "=", "programa_tipo.pro_tip_id")
            ->join('programa_calificacion', 'programa_tipo.pro_tip_id', "=", "programa_calificacion.pro_tip_id")
            ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
            ->select(
                    'programa_modulo.pm_id', 
                    'programa_modulo.pm_nombre', 
                    'programa_modulo.pro_id', 
                    'programa_modulo.pm_estado', 
                    'programa_calificacion.pc_id', 
                    'programa_tipo_calificacion.ptc_id', 
                    'programa_tipo_calificacion.ptc_nombre', 
                    'programa_tipo_calificacion.ptc_nota', 
            )
            ->whereIn('programa_modulo.pm_estado', ['activo', 'vista'])
            ->where('programa.pro_id', 11)
            ->orderBy('programa_modulo.pm_nombre')
            ->orderBy('programa_tipo_calificacion.ptc_id')
            ->get();
        
        
        // Agrupar las inscripciones por pro_id
        // $baucheres= ProgramaBaucher::all();
        // Contar los baucheres por sede usando el sede_id
        $sede_id = encrypt($sede_id);        
        //$mapPersona = MapPersona::paginate(100);
        return view('backend.pages.calificacion.indexx', compact(['inscripciones','sede_id', 
        'modulos', 'facilitador_nombre','facilitador_apellidos','facilitador_celular','programa']));
    }
    public function indexxx(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('calificacion.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún calificacion!');
        }

        $sede_id = decrypt($request->sede_id);
        $pro_id = decrypt($request->pro_id);
        
        $inscripciones = DB::table('programa_inscripcion')
            ->join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_turno', 'programa_inscripcion.pro_tur_id', '=', 'programa_turno.pro_tur_id')
            ->join('sede', 'programa_inscripcion.sede_id', '=', 'sede.sede_id')
            ->join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
            ->join('programa_inscripcion_estado', 'programa_inscripcion.pie_id', '=', 'programa_inscripcion_estado.pie_id')
            ->where('sede.sede_id', $sede_id)
            ->where('programa.pro_id', $pro_id)
            ->where('programa_inscripcion.pi_estado', "=",'activo')
            ->where('programa_inscripcion.pie_id', "=",2)
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
        $facilitador = DB::table('admins')
            ->select(
                'admins.nombre',
                'admins.apellidos',
                'admins.celular',
                'admins.cargo',
                'admins.sede_ids',
                'roles.name'
            )
            ->join('model_has_roles', 'admins.id', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->whereJsonContains('admins.sede_ids', (string) $sede_id)
            ->whereJsonContains('admins.pro_ids', (string) $pro_id)
            ->where('model_has_roles.role_id', '=', 3)
            ->first();
        // dd($facilitador);
        

        // Manejar la información del facilitador
        if ($facilitador) {
            $facilitador_nombre = $facilitador->nombre;
            $facilitador_apellidos = $facilitador->apellidos;
            $facilitador_celular = $facilitador->celular;
        } else {
            if ($pro_id == 9){
                $facilitador_nombre = 'ROXANA';
                $facilitador_apellidos = 'ARAUJO ALIAGA';
                $facilitador_celular = '77216668';
            }else{
                $facilitador_nombre = 'No encontrado';
                $facilitador_apellidos = 'No encontrado';
                $facilitador_celular = 'No encontrado';
            }
        }
        $inscripciones = $inscripciones->get();
       
        $modulos = DB::table('programa_modulo')
            ->join('programa', 'programa.pro_id', "=", "programa_modulo.pro_id")
            ->join('programa_tipo', 'programa.pro_tip_id', "=", "programa_tipo.pro_tip_id")
            ->join('programa_calificacion', 'programa_tipo.pro_tip_id', "=", "programa_calificacion.pro_tip_id")
            ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
            ->select(
                    'programa_modulo.pm_id', 
                    'programa_modulo.pm_nombre', 
                    'programa_modulo.pro_id', 
                    'programa_modulo.pm_estado', 
                    'programa_calificacion.pc_id', 
                    'programa_tipo_calificacion.ptc_id', 
                    'programa_tipo_calificacion.ptc_nombre', 
                    'programa_tipo_calificacion.ptc_nota', 
            )->whereIn('programa_modulo.pm_estado', ['activo', 'vista'])
            ->where('programa.pro_id', $pro_id)
            ->orderBy('programa_modulo.pm_nombre')
            ->orderBy('programa_tipo_calificacion.ptc_id')
            ->get();
        
        // Agrupar las inscripciones por pro_id
        // $baucheres= ProgramaBaucher::all();
        // Contar los baucheres por sede usando el sede_id
        $sede_id = encrypt($sede_id);        
        //$mapPersona = MapPersona::paginate(100);
        return view('backend.pages.calificacion.indexx', compact(['inscripciones','sede_id', 
        'modulos','facilitador_nombre','facilitador_apellidos','facilitador_celular']));
    }
    public static  function obtenerCalificacion($pm_id, $pi_id, $pc_id){
        return $calificaciones = DB::table('calificacion_participante')
            ->join('programa_calificacion', 'calificacion_participante.pc_id', "=", "programa_calificacion.pc_id")
            ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', "=", "programa_tipo_calificacion.ptc_id")
            ->where('pm_id', $pm_id)
            ->where('pi_id', $pi_id)
            ->where('pc_id', $pc_id)
            ->first();
    }


    public function storeCalificacion(Request $request, $pi_id, $pm_id, $pc_id)
    {
        // if (is_null($this->user) || !$this->user->can('calificacion.edit')) {
        //     abort(403, 'Lo siento !! ¡No estás autorizado para editar la calificación!');
        // }
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
            // Actualizar o crear la calificación para el programa total
            $inscripcion = ProgramaInscripcion::where("pi_id", "=", $pi_id)
            ->update([
                'pi_modulo' => '1',
            ]);
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
                if($total == 0){
                    $calificacionTotal->cp_estado = 'abandono';
                }else{
                    $calificacionTotal->cp_estado = 'reprobado';
                }
            } else {
                $calificacionTotal->cp_estado = 'aprobado';
            }
            $calificacionTotal->save();
        }
        // Redireccionar con mensaje de éxito
        return redirect()->back()->with('success', 'Calificación guardada correctamente.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
    public function reporteCalificacionPdf($sede_id, $pro_id, $pm_id, $pro_tur_id)
    {
        if (is_null($this->user) || !$this->user->can('calificacion.reporte')) {
            abort(403, 'Lo siento !! ¡No estás autorizado para exportar el reporte!');
        }
        // Desencriptar los IDs
        $sede_id = decrypt($sede_id);
        $pro_id = decrypt($pro_id);
        $pm_id = decrypt($pm_id);
        $pro_tur_id = decrypt($pro_tur_id);
        

        $inscritos = DB::table('programa_inscripcion as pi')
            ->join('map_persona as per', 'per.per_id', '=', 'pi.per_id')
            ->join('calificacion_participante as cp', 'cp.pi_id', '=', 'pi.pi_id')
            ->join('sede as sede', 'sede.sede_id', '=', 'pi.sede_id')
            ->join('programa as pro', 'pro.pro_id', '=', 'pi.pro_id')
            ->select(
                'pi.pi_id',
                'pi.pro_tur_id',
                'per.per_ci',
                'per.per_complemento',
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
            ->where('cp.pm_id', '=', $pm_id)
            ->where('pi.pi_estado', '=', 'activo')
            ->where('pi.pie_id', 2)
            ->whereNotNull('cp.pi_id') // Filtra solo los que tienen calificación registrada
            ->groupBy('pi.pi_id', 'pi.pro_tur_id', 'per.per_ci','per.per_complemento', 'per.per_apellido1', 'per.per_apellido2', 'per.per_nombre1', 'per.per_nombre2', 'sede.sede_nombre')
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
                'pt.pro_tip_id',
                DB::raw('UPPER(prom.pm_nombre) as pm_nombre'),
                DB::raw('UPPER(pt.pro_tip_nombre) as programa_tipo'),
                DB::raw('UPPER(pro.pro_nombre) as programa'),
                DB::raw('UPPER(pm.pm_nombre) as modulo'),
                DB::raw('UPPER(pm.pm_descripcion) as modulo_completo'),
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
        // Manejar la información del responsable
        if ($responsable) {
            $responsable_nombre =       $responsable->nombre;
            $responsable_apellidos =    $responsable->apellidos;
            $responsable_cargo =        $responsable->name;
        } else {
            $responsable_nombre = 'No encontrado';
            $responsable_apellidos = 'No encontrado';
            $responsable_cargo = 'No encontrado';
        }

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
            }elseif ($pro_id == 4) {
                $facilitador_nombre =       $responsable->nombre;
                $facilitador_apellidos =    $responsable->apellidos;
                $facilitador_cargo =        'FACILITADOR';
            }
            else{
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
        
        $imagen5 = public_path() . "/assets/image/logoprofeiippminedu.png";
        $logo5 = base64_encode(file_get_contents($imagen5));
        
        $imagen4 = public_path() . "/img/logos/fondo.jpg";
        $fondo = base64_encode(file_get_contents($imagen4));
        $fechaActual = date('d \d\e M Y h:i a');
        // Array para traducir los meses de inglés a español
        $mesesIngles = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $mesesEspanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        // Reemplazar el nombre del mes en inglés por español
        $fechaActual = str_replace($mesesIngles, $mesesEspanol, $fechaActual);
        $totalMatriculados = $inscritos->count();
        // $datosQr = 'FAC: '.$facilitador_nombre.' '.$facilitador_apellidos.' |S:'.$sede_id.' |P:'.$pro_id.' |G:'.$pro_tur_id.' |M:'.$pm_id.' |MATRICULADOS:'.$totalMatriculados.' |F: '.$fechaActual;
        
        $barcode = new DNS1D();
        // Asegurarte de que cada ID tenga 3 dígitos, añadiendo ceros a la izquierda si es necesario
        $sede_id_formatted = str_pad($sede_id, 4, '0', STR_PAD_LEFT);
        $pro_id_formatted = str_pad($pro_id, 4, '0', STR_PAD_LEFT);
        $pm_id_formatted = str_pad($pm_id, 4, '0', STR_PAD_LEFT);
        $pro_tur_id_formatted = str_pad($pro_tur_id, 4, '0', STR_PAD_LEFT);
        // Concatenar los IDs formateados
        $dato = $sede_id . "-" . $pro_id . "-" . $pm_id . "-" . $pro_tur_id;
        $dato_encrypt = $sede_id_formatted . "-" . $pro_id_formatted . "-" . $pm_id_formatted . "-" . $pro_tur_id_formatted;
        $hash = md5('PROFE-'.$dato_encrypt);

        $exists = DB::table('barcode')->where('bar_md5', $hash)->exists();
        if (!$exists) {
            // Insertar si el hash no existe
            DB::table('barcode')->insert([
                'bar_md5' => $hash,
                'bar_descripcion' => $dato_encrypt,
                'bar_tipo' => 'REGISTRO ACADEMICO', // O el valor que desees
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }else{
            // Si el hash ya existe, actualizar solo la columna 'updated_at'
            DB::table('barcode')
                ->where('bar_md5', $hash)
                ->update(['updated_at' => now()]);
        }
        $barcodeImage = $barcode->getBarcodePNG($dato, 'C128', 2.5, 60); 
        $codigoBarra = $hash;


        // $qr = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($datosQr));
        $pdf = PDF::loadView('backend.pages.calificacion.partials.reporteCalificacionPdf', compact('inscritos', 'calificacion_participantes', 'datos_programa' , 'responsable_nombre', 'responsable_apellidos', 'responsable_cargo',
        'facilitador_nombre', 'facilitador_apellidos', 'facilitador_cargo','barcodeImage','codigoBarra','logo1', 'logo2', 'logo3', 'logo5', 'fondo'));
        // VERTICAL
        $pdf->setPaper('Letter', 'portrait');
        // HORIZONTAL
        // $pdf->setPaper('Letter', 'landscape');
        //
        return $pdf->stream('Calificacion ' . $datos_programa->pro_nombre_abre ." " .$datos_programa->modulo . '.pdf');
        // return $pdf->download('mi-archivo.pdf');
    }
    public function reporteCalificacionInvPdf($sede_id, $pro_id, $pm_id, $pro_tur_id)
    {
        if (is_null($this->user) || !$this->user->can('calificacion.reporte')) {
            abort(403, 'Lo siento !! ¡No estás autorizado para exportar el reporte!');
        }
        // Desencriptar los IDs
        $sede_id = decrypt($sede_id);
        $pro_id = decrypt($pro_id);
        $pm_id = decrypt($pm_id);
        $pro_tur_id = decrypt($pro_tur_id);
        

        $inscritos = DB::table('programa_inscripcion as pi')
            ->join('map_persona as per', 'per.per_id', '=', 'pi.per_id')
            ->join('calificacion_participante as cp', 'cp.pi_id', '=', 'pi.pi_id')
            ->join('sede as sede', 'sede.sede_id', '=', 'pi.sede_id')
            ->join('programa as pro', 'pro.pro_id', '=', 'pi.pro_id')
            ->select(
                'pi.pi_id',
                'pi.pro_tur_id',
                'per.per_ci',
                'per.per_complemento',
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
            ->where('cp.pm_id', '=', $pm_id)
            ->where('pi.pi_estado', '=', 'activo')
            ->where('pi.pie_id', '=', 2)
            ->whereNotNull('cp.pi_id') // Filtra solo los que tienen calificación registrada
            ->groupBy('pi.pi_id', 'pi.pro_tur_id', 'per.per_ci','per.per_complemento', 'per.per_apellido1', 'per.per_apellido2', 'per.per_nombre1', 'per.per_nombre2', 'sede.sede_nombre')
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
                'pt.pro_tip_id',
                DB::raw('UPPER(prom.pm_nombre) as pm_nombre'),
                DB::raw('UPPER(pt.pro_tip_nombre) as programa_tipo'),
                DB::raw('UPPER(pro.pro_nombre) as programa'),
                DB::raw('UPPER(pm.pm_nombre) as modulo'),
                DB::raw('UPPER(pm.pm_descripcion) as modulo_completo'),
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
            }elseif ($pro_id == 4) {
                $facilitador_nombre =       $responsable->nombre;
                $facilitador_apellidos =    $responsable->apellidos;
                $facilitador_cargo =        'FACILITADOR';
            }
            else{
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
        
        $imagen5 = public_path() . "/assets/image/logoprofeiippminedu.png";
        $logo5 = base64_encode(file_get_contents($imagen5));
        
        $imagen4 = public_path() . "/img/logos/fondo.jpg";
        $fondo = base64_encode(file_get_contents($imagen4));
        $fechaActual = date('d \d\e M Y h:i a');
        // Array para traducir los meses de inglés a español
        $mesesIngles = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $mesesEspanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        // Reemplazar el nombre del mes en inglés por español
        $fechaActual = str_replace($mesesIngles, $mesesEspanol, $fechaActual);
        $totalMatriculados = $inscritos->count();
        // $datosQr = 'FAC: '.$facilitador_nombre.' '.$facilitador_apellidos.' |S:'.$sede_id.' |P:'.$pro_id.' |G:'.$pro_tur_id.' |M:'.$pm_id.' |MATRICULADOS:'.$totalMatriculados.' |F: '.$fechaActual;
        
        $barcode = new DNS1D();
        // Asegurarte de que cada ID tenga 3 dígitos, añadiendo ceros a la izquierda si es necesario
        $sede_id_formatted = str_pad($sede_id, 4, '0', STR_PAD_LEFT);
        $pro_id_formatted = str_pad($pro_id, 4, '0', STR_PAD_LEFT);
        $pm_id_formatted = str_pad($pm_id, 4, '0', STR_PAD_LEFT);
        $pro_tur_id_formatted = str_pad($pro_tur_id, 4, '0', STR_PAD_LEFT);
        // Concatenar los IDs formateados
        $dato = $sede_id . "-" . $pro_id . "-" . $pm_id . "-" . $pro_tur_id;
        $dato_encrypt = $sede_id_formatted . "-" . $pro_id_formatted . "-" . $pm_id_formatted . "-" . $pro_tur_id_formatted;
        $hash = md5('PROFE-'.$dato_encrypt);

        $exists = DB::table('barcode')->where('bar_md5', $hash)->exists();
        if (!$exists) {
            // Insertar si el hash no existe
            DB::table('barcode')->insert([
                'bar_md5' => $hash,
                'bar_descripcion' => $dato_encrypt,
                'bar_tipo' => 'REGISTRO ACADEMICO', // O el valor que desees
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }else{
            // Si el hash ya existe, actualizar solo la columna 'updated_at'
            DB::table('barcode')
                ->where('bar_md5', $hash)
                ->update(['updated_at' => now()]);
        }
        $barcodeImage = $barcode->getBarcodePNG($dato, 'C128', 2.5, 60); 
        $codigoBarra = $hash;


        // $qr = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($datosQr));
        $pdf = PDF::loadView('backend.pages.calificacion.partials.reporteCalificacionPdf', compact('inscritos', 'calificacion_participantes', 'datos_programa' , 'responsable_nombre', 'responsable_apellidos', 'responsable_cargo',
        'facilitador_nombre', 'facilitador_apellidos', 'facilitador_cargo','barcodeImage','codigoBarra','logo1', 'logo2', 'logo3', 'logo5', 'fondo'));
        // VERTICAL
        $pdf->setPaper('Letter', 'portrait');
        // HORIZONTAL
        // $pdf->setPaper('Letter', 'landscape');
        //
        return $pdf->stream('Calificacion ' . $datos_programa->pro_nombre_abre ." " .$datos_programa->modulo . '.pdf');
        // return $pdf->download('mi-archivo.pdf');
    }
}
