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
use App\Models\ProgramaRestriccion;
use App\Models\ProgramaTipo;
use App\Models\ProgramaModulo;
use App\Models\CalificacionParticipante;
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
use Carbon\Carbon;

// Establecer el idioma a español
Carbon::setLocale('es');
class ActaConclusionController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('inscripcion.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún inscripcion!');
        }

        $programa = DB::table('programa')
                        ->join('programa_tipo', 'programa.pro_tip_id', '=', 'programa_tipo.pro_tip_id')
                        ->where('programa.pro_tip_id', 3);
        $sede = DB::table('sede');

        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) { // Verifica si $proIds no está vacío
                $programa->whereIn('pro_id', $proIds);
            }
        }
        if (!is_null($this->user->sede_ids)) {
            $sedeIds = json_decode($this->user->sede_ids);
            if (!empty($sedeIds)) { // Verifica si $proIds no está vacío
                $sede->whereIn('sede_id', $sedeIds);
            }
        }
        $programa = $programa->get();
        $sede = $sede->get();
        $programaCount = $programa->count();
        $sedeCount = $sede->count();
        return view('backend.pages.acta_conclusion.index', compact('programa','sede','sedeCount', 'programaCount'));
    }
    public function filtrarReporte(Request $request)
    {
        $sedeId = $request->input('sede_id');
        $programaId = $request->input('programa_id');

        $inscripciones = ProgramaInscripcion::join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('map_especialidad', 'map_persona.esp_id', '=', 'map_especialidad.esp_id')
            ->join('map_cargo', 'map_persona.car_id', '=', 'map_cargo.car_id')
            ->join('map_nivel', 'map_persona.niv_id', '=', 'map_nivel.niv_id')
            ->join('map_categoria', 'map_persona.cat_id', '=', 'map_categoria.cat_id')
            ->join('map_subsistema', 'map_persona.sub_id', '=', 'map_subsistema.sub_id')
            ->join('genero', 'map_persona.gen_id', '=', 'genero.gen_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_turno', 'programa_inscripcion.pro_tur_id', '=', 'programa_turno.pro_tur_id')
            ->join('sede', 'programa_inscripcion.sede_id', '=', 'sede.sede_id')
            ->leftJoin('programa_baucher', 'programa_baucher.pi_id', '=', 'programa_inscripcion.pi_id')
            ->where('programa_inscripcion.sede_id', $sedeId)
            ->where('programa_inscripcion.pro_id', $programaId)
            ->select(
                'programa_inscripcion.pi_id',
                'programa_inscripcion.pi_doc_digital',
                'programa_inscripcion.pi_modulo',
                'programa_inscripcion.per_id',
                'programa_inscripcion.pro_id',
                'programa_inscripcion.pro_tur_id',
                'programa_inscripcion.sede_id',
                'programa_inscripcion.created_at',
                'programa_turno.pro_tur_nombre',
                'map_persona.per_rda',
                'map_persona.per_ci',
                'map_persona.per_complemento',
                'map_persona.per_nombre1',
                'map_persona.per_nombre2',
                'map_persona.per_apellido1',
                'map_persona.per_apellido2',
                'map_persona.per_fecha_nacimiento',
                'map_persona.per_celular',
                'map_persona.per_correo',
                'map_persona.per_en_funcion',
                'map_especialidad.esp_id',
                'map_especialidad.esp_nombre',
                'map_categoria.cat_id',
                'map_categoria.cat_nombre',
                'map_cargo.car_id',
                'map_cargo.car_nombre',
                'map_subsistema.sub_id',
                'map_subsistema.sub_nombre',
                'map_nivel.niv_id',
                'map_nivel.niv_nombre',
                'genero.gen_id',
                'genero.gen_nombre',
                'programa.pro_nombre',
                'programa.pro_nombre_abre',
                'sede.sede_nombre',
                'sede.sede_nombre_abre',
                DB::raw('SUM(programa_baucher.pro_bau_monto) AS total_deposito')
            )
            ->groupBy(
                'programa_inscripcion.pi_id',
                'programa_inscripcion.pi_doc_digital',
                'programa_inscripcion.pi_modulo',
                'programa_inscripcion.per_id',
                'programa_inscripcion.pro_id',
                'programa_inscripcion.pro_tur_id',
                'programa_inscripcion.sede_id',
                'programa_inscripcion.created_at',
                'programa_turno.pro_tur_nombre',
                'map_persona.per_rda',
                'map_persona.per_ci',
                'map_persona.per_complemento',
                'map_persona.per_nombre1',
                'map_persona.per_nombre2',
                'map_persona.per_apellido1',
                'map_persona.per_apellido2',
                'map_persona.per_fecha_nacimiento',
                'map_persona.per_celular',
                'map_persona.per_correo',
                'map_persona.per_en_funcion',
                'map_especialidad.esp_id',
                'map_especialidad.esp_nombre',
                'map_categoria.cat_id',
                'map_categoria.cat_nombre',
                'map_cargo.car_id',
                'map_cargo.car_nombre',
                'map_subsistema.sub_id',
                'map_subsistema.sub_nombre',
                'map_nivel.niv_id',
                'map_nivel.niv_nombre',
                'genero.gen_id',
                'genero.gen_nombre',
                'programa.pro_nombre',
                'programa.pro_nombre_abre',
                'sede.sede_nombre',
                'sede.sede_nombre_abre'
            )
            ->orderBy('per_apellido1')
            ->orderBy('per_apellido2')
            ->get()
            ->groupBy('pro_tur_id');
        // Obtener facilitador
        $facilitador = DB::table('admins')
            ->select(
                'admins.nombre',
                'admins.apellidos',
                'admins.cargo',
                'admins.celular',
                'admins.sede_ids',
                'roles.name'
            )
            ->join('model_has_roles', 'admins.id', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->whereJsonContains('admins.sede_ids', $sedeId)
            ->whereJsonContains('admins.pro_ids', $programaId)
            ->where('model_has_roles.role_id', '=', 3)
            ->first();
        // dd($facilitador);
        

        // Manejar la información del facilitador
        if ($facilitador) {
            $facilitador_nombre = $facilitador->nombre;
            $facilitador_apellidos = $facilitador->apellidos;
            $facilitador_cargo = $facilitador->name;
            $facilitador_celular = $facilitador->celular;
        } else {
            $facilitador_nombre = 'No encontrado';
            $facilitador_apellidos = 'No encontrado';
            $facilitador_cargo = 'No encontrado';
            $facilitador_celular = 'No encontrado';
        }   
        
        // Mostrar información del facilitador
        $output = '<div class="facilitador-info">';
        $output .= '<p><strong>Facilitador: </strong> ' . $facilitador_nombre . ' ' . $facilitador_apellidos . '</p>';
        $output .= '<p><strong>Celular: </strong> ' . $facilitador_celular . '</p>';
        $output .= '</div>';

        // Iniciar las pestañas
        $output .= '<ul class="nav nav-tabs" id="myTab" role="tablist">';
        $tabContent = '<div class="tab-content" id="myTabContent">';

        $activeClass = 'active';
        $index = 0;
        $modulos = ProgramaModulo::where('pro_id',$programaId)->get();        
        foreach ($inscripciones as $proTurId => $grupoInscripciones) {
            $tabId = 'tab-' . $proTurId;
            $proTurNombre = $grupoInscripciones->first()->pro_tur_nombre;
            $countInscripciones = $grupoInscripciones->count();
            
            // Crear una pestaña para cada grupoabout:blank#blocked
            $output .= '<li class="nav-item" role="presentation">
                            <a class="nav-link ' . ($index === 0 ? 'active' : '') . '" id="' . $tabId . '-tab" data-bs-toggle="tab" href="#' . $tabId . '" role="tab" aria-controls="' . $tabId . '" aria-selected="' . ($index === 0 ? 'true' : 'false') . '">' . 
                                $proTurNombre . 
                                ' <span class="badge bg-primary">' . $countInscripciones . '</span>
                            </a>
                        </li>';
            
            // Crear el contenido de la pestaña
            $tabContent .= '<div class="tab-pane fade ' . ($index === 0 ? 'show active' : '') . '" id="' . $tabId . '" role="tabpanel" aria-labelledby="' . $tabId . '-tab">';
            $tabContent .= '<div class="dt-responsive table-responsive"><table class="table table-striped table-bordered table-hover" id="table-' . $proTurId . '">';
            $tabContent .= '<thead class="table-dark"><tr>
                            <th>#</th>
                            <th>C.I.</th>
                            <th>Apellidos y Nombres</th>
                            <th>Total Pagó</th>
                            <th>Módulos</th>
                            <th>Acciones</th>
                        </tr></thead><tbody>';
        
            $rowIndex = 1;
            foreach ($grupoInscripciones as $inscripcion) {
                $nombreCompleto = $inscripcion->per_apellido1 . ' ' . $inscripcion->per_apellido2 . ', ' . $inscripcion->per_nombre1 . ' ' . $inscripcion->per_nombre2;
        
                // Inicializa variables para el promedio
                $promedio = 0;
                $moduloTitulos = [];
                $totalNotas = 0;
                $cantidadCalificaciones = 0;
        
                foreach ($modulos as $index => $modulo) {
                    $calificacion = CalificacionParticipante::where('pi_id', $inscripcion->pi_id)
                        ->where('pm_id', $modulo->pm_id)
                        ->where('pc_id', 8)
                        ->first();
        
                    $nota = $calificacion ? $calificacion->cp_puntaje : 'N/A';
                    
                    // Guarda el título del módulo con su calificación
                    $moduloTitulos[] = '<span class="badge ' . ($nota !== 'N/A' ? 'bg-success' : 'bg-danger') . '"><strong>M ' . ($index + 1) . ':</strong> ' . ($nota !== 'N/A' ? $nota : 'N/A') . '</span>';
                    
                    // Acumula notas para el promedio si es un valor numérico
                    if (is_numeric($nota)) {
                        $totalNotas += $nota;
                        $cantidadCalificaciones++;
                    }
                }
        
                // Calcular el promedio si hay calificaciones
                if ($cantidadCalificaciones > 0) {
                    $promedio = round($totalNotas / $cantidadCalificaciones);
                } else {
                    $promedio = 'N/A';
                }
        
                // Concatenar los módulos en una sola fila y agregar el promedio
                $moduloOutput = implode('  ', $moduloTitulos);
                $moduloOutput .= '  <span class="badge bg-primary"><strong>Prom:</strong> ' . ($promedio !== 'N/A' ? $promedio : 'N/A') . '</span>';
                
                // Aplicar clases de colores solo a celdas específicas
                $totalDepositoClass = $inscripcion->total_deposito >= 1500 ? 'table-success' : 'table-warning';
                $promedioClass = ($promedio !== 'N/A' && $promedio < 70) ? 'table-warning' : '';
        
                // Agregar el contenido al HTML de la tabla
                $tabContent .= '<tr>';
                $tabContent .= '<td>' . $rowIndex++ . '</td>';
                $tabContent .= '<td>' . $inscripcion->per_ci . ' ' . $inscripcion->per_complemento . '</td>';
                $tabContent .= '<td>' . $nombreCompleto . '</td>';
                $tabContent .= '<td class="' . $totalDepositoClass . '">Bs ' . number_format($inscripcion->total_deposito, 0, '.', '.') . '</td>';
                $tabContent .= '<td class="' . $promedioClass . '"><div class="text-center">' . $moduloOutput . '</div></td>';
                $tabContent .= '<td>
                                    <button class="btn btn-sm icofont icofont-owl-look ver-detalles" data-id="' . $inscripcion->pi_id . '" data-bs-toggle="tooltip" title="Ver Detalles"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-warning editar-inscripcion" data-id="' . $inscripcion->pi_id . '" data-bs-toggle="tooltip" title="Editar Inscripción"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger eliminar-inscripcion" data-id="' . $inscripcion->pi_id . '" data-bs-toggle="tooltip" title="Eliminar Inscripción"><i class="bi bi-trash"></i></button>
                                </td>';
                $tabContent .= '</tr>';
            }
            $tabContent .= '</tbody></table></div></div>';
            $index++;
        }
        
        

        $output .= '</ul>'; // Cerrar lista de pestañas
        $tabContent .= '</div>'; // Cerrar contenido de pestañas

        return response()->json($output . $tabContent);
    }
    public function obtenerDetallesPersona($id){
        if (is_null($this->user) || !$this->user->can('inscripcion.view')) {
            abort(403, 'Lo siento!! ¡No estás autorizado a ver ninguna inscripción!');
        }
        $inscripcion = ProgramaInscripcion::join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('map_especialidad', 'map_persona.esp_id', '=', 'map_especialidad.esp_id')
            ->join('map_cargo', 'map_persona.car_id', '=', 'map_cargo.car_id')
            ->join('map_nivel', 'map_persona.niv_id', '=', 'map_nivel.niv_id')
            ->join('map_categoria', 'map_persona.cat_id', '=', 'map_categoria.cat_id')
            ->join('map_subsistema', 'map_persona.sub_id', '=', 'map_subsistema.sub_id')
            ->join('genero', 'map_persona.gen_id', '=', 'genero.gen_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_turno', 'programa_inscripcion.pro_tur_id', '=', 'programa_turno.pro_tur_id')
            ->join('sede', 'programa_inscripcion.sede_id', '=', 'sede.sede_id')
            ->leftJoin('programa_baucher', 'programa_baucher.pi_id', '=', 'programa_inscripcion.pi_id')
            ->where('programa_inscripcion.pi_id', $id)
            ->select(
                'programa_inscripcion.pi_id',
                'programa_inscripcion.pi_doc_digital',
                'programa_inscripcion.pi_modulo',
                'programa_inscripcion.per_id',
                'programa_inscripcion.pro_id',
                'programa_inscripcion.pro_tur_id',
                'programa_inscripcion.sede_id',
                'programa_inscripcion.created_at',
                'programa_turno.pro_tur_nombre',
                'map_persona.per_rda',
                'map_persona.per_ci',
                'map_persona.per_complemento',
                'map_persona.per_nombre1',
                'map_persona.per_nombre2',
                'map_persona.per_apellido1',
                'map_persona.per_apellido2',
                'map_persona.per_fecha_nacimiento',
                'map_persona.per_celular',
                'map_persona.per_correo',
                'map_persona.per_en_funcion',
                'map_especialidad.esp_id',
                'map_especialidad.esp_nombre',
                'map_categoria.cat_id',
                'map_categoria.cat_nombre',
                'map_cargo.car_id',
                'map_cargo.car_nombre',
                'map_subsistema.sub_id',
                'map_subsistema.sub_nombre',
                'map_nivel.niv_id',
                'map_nivel.niv_nombre',
                'genero.gen_id',
                'genero.gen_nombre',
                'programa.pro_nombre',
                'programa.pro_nombre_abre',
                'sede.sede_nombre',
                'sede.sede_nombre_abre',
                DB::raw('SUM(programa_baucher.pro_bau_monto) AS total_deposito')
            )
            ->groupBy(
                'programa_inscripcion.pi_id',
                'programa_inscripcion.pi_doc_digital',
                'programa_inscripcion.pi_modulo',
                'programa_inscripcion.per_id',
                'programa_inscripcion.pro_id',
                'programa_inscripcion.pro_tur_id',
                'programa_inscripcion.sede_id',
                'programa_inscripcion.created_at',
                'programa_turno.pro_tur_nombre',
                'map_persona.per_rda',
                'map_persona.per_ci',
                'map_persona.per_complemento',
                'map_persona.per_nombre1',
                'map_persona.per_nombre2',
                'map_persona.per_apellido1',
                'map_persona.per_apellido2',
                'map_persona.per_fecha_nacimiento',
                'map_persona.per_celular',
                'map_persona.per_correo',
                'map_persona.per_en_funcion',
                'map_especialidad.esp_id',
                'map_especialidad.esp_nombre',
                'map_categoria.cat_id',
                'map_categoria.cat_nombre',
                'map_cargo.car_id',
                'map_cargo.car_nombre',
                'map_subsistema.sub_id',
                'map_subsistema.sub_nombre',
                'map_nivel.niv_id',
                'map_nivel.niv_nombre',
                'genero.gen_id',
                'genero.gen_nombre',
                'programa.pro_nombre',
                'programa.pro_nombre_abre',
                'sede.sede_nombre',
                'sede.sede_nombre_abre'
            )
            ->first();

          
               
        return response()->json($inscripcion);
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
    public function searchRda(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('inscripcion.create') || !$this->user->can('inscripcion.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }
        $rda = $request->rda;
        $person = MapPersona::where('per_rda', $rda)->first();

        if ($person) {
            return response()->json([
                'success' => true,
                'person' => $person,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Persona no encontrada',
            ]);
        }
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
        $inscripcion = DB::table('programa_inscripcion')
            ->join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('map_especialidad', 'map_persona.esp_id', '=', 'map_especialidad.esp_id')
            ->join('map_cargo', 'map_persona.car_id', '=', 'map_cargo.car_id')
            ->join('map_nivel', 'map_persona.niv_id', '=', 'map_nivel.niv_id')
            ->join('map_categoria', 'map_persona.cat_id', '=', 'map_categoria.cat_id')
            ->join('map_subsistema', 'map_persona.sub_id', '=', 'map_subsistema.sub_id')
            ->join('genero', 'map_persona.gen_id', '=', 'genero.gen_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_turno', 'programa_inscripcion.pro_tur_id', '=', 'programa_turno.pro_tur_id')
            ->join('sede', 'programa_inscripcion.sede_id', '=', 'sede.sede_id')
            ->join('programa_inscripcion_estado', 'programa_inscripcion.pie_id', '=', 'programa_inscripcion_estado.pie_id')
            ->where('programa_inscripcion.pi_id', $pi_id) // Filtrar por pi_id
            ->select('programa_inscripcion.*', 'map_persona.*', 'map_especialidad.esp_nombre', 'map_cargo.car_nombre',
                'programa.pro_nombre', 'programa.pro_costo', 'map_subsistema.sub_nombre', 'map_nivel.niv_nombre',
                'map_categoria.cat_nombre', 'genero.gen_nombre',
                'programa_turno.pro_tur_nombre', 'sede.sede_nombre', 'programa_inscripcion_estado.pie_nombre')
            ->first();
        $sede = Sede::join('departamento', 'sede.dep_id', '=', 'departamento.dep_id')
            ->where('sede.sede_id', $inscripcion->sede_id)
            ->select('sede.*', 'departamento.dep_nombre') // Selecciona los campos necesarios
            ->first();
        // Obtener todos los programas filtrados por pro_ids del usuario
        $programa = Programa::where('pro_id', $inscripcion->pro_id)
            ->select('programa.*') // Selecciona los campos necesarios
            ->first();
        // Obtener los bauchers relacionados con la inscripción filtrada por pi_id
        $bauchers = ProgramaBaucher::where('pi_id', $pi_id)->get();
        $totalMonto = ProgramaBaucher::where('pi_id', $pi_id)->sum('pro_bau_monto');
        $inscripcionestado = ProgramaInscripcionEstado::all();
        return view('backend.pages.inscripcion.edit', compact('inscripcion', 'programa', 'sede', 'bauchers','totalMonto','inscripcionestado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inscripcionId = decrypt($id); 
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
}
