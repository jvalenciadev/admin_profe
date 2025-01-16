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
use App\Models\MapPersonaNr;
use App\Models\ActaConclusion;
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
        if (is_null($this->user) || !$this->user->can('personaprograma.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún inscripcion!');
        }

        $programa = DB::table('programa')
                        ->join('programa_tipo', 'programa.pro_tip_id', '=', 'programa_tipo.pro_tip_id');
                        // ->where('programa.pro_tip_id', 3);
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
    public function indexParticipantes()
    {
        if (is_null($this->user) || !$this->user->can('personaprograma.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún inscripcion!');
        }
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
            ->where('programa_inscripcion.pi_estado', "activo")
            ->where('programa_inscripcion.pie_id', 2)
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
        ->groupBy('pro_tur_id');
        
        
        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) { // Verifica si $proIds no está vacío
                $inscripciones->whereIn('programa_inscripcion.pro_id', $proIds);
            }
        }
        if (!is_null($this->user->sede_ids)) {
            $sedeIds = json_decode($this->user->sede_ids);
            if (!empty($sedeIds)) { // Verifica si $proIds no está vacío
                $inscripciones->whereIn('programa_inscripcion.sede_id', $sedeIds);
            }
        }
        $inscripciones = $inscripciones->get();
        return view('backend.pages.acta_conclusion.index_persona');
    }
    public function participanteEncontrado(Request $request) {
        if (is_null($this->user) || !$this->user->can('personaprograma.view')) {
            abort(403, 'Lo siento !! ¡No estás autorizado a ver ningún inscripcion!');
        }
        $buscador = $request->input('ci');
        // Separar CI y complemento usando el formato 'ci-complemento'
        $partes = explode('-', $buscador);
        $ci = $partes[0] ?? null; // CI
        $complemento = $partes[1] ?? null; // Complemento (puede ser vacío)

        // Validar que CI no sea nulo
        if (!$ci) {
            return response()->json('<p class="text-danger">El formato del CI y complemento es incorrecto.</p>');
        }
        // Realiza la búsqueda del participante en la base de datos por CI
        $inscripcion = ProgramaInscripcion::join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('map_especialidad', 'map_persona.esp_id', '=', 'map_especialidad.esp_id')
            ->join('map_cargo', 'map_persona.car_id', '=', 'map_cargo.car_id')
            ->join('map_nivel', 'map_persona.niv_id', '=', 'map_nivel.niv_id')
            ->join('map_categoria', 'map_persona.cat_id', '=', 'map_categoria.cat_id')
            ->join('map_subsistema', 'map_persona.sub_id', '=', 'map_subsistema.sub_id')
            ->join('genero', 'map_persona.gen_id', '=', 'genero.gen_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_tipo', 'programa.pro_tip_id', '=', 'programa_tipo.pro_tip_id')
            ->join('programa_turno', 'programa_inscripcion.pro_tur_id', '=', 'programa_turno.pro_tur_id')
            ->join('sede', 'programa_inscripcion.sede_id', '=', 'sede.sede_id')
            ->leftJoin('programa_baucher', 'programa_baucher.pi_id', '=', 'programa_inscripcion.pi_id')
            ->where('map_persona.per_ci', $ci)
            ->where('map_persona.per_complemento', $complemento)
            ->where('programa_inscripcion.pi_estado', "activo")
            ->where('programa_inscripcion.pie_id', 2)
            ->where('programa.pro_id', "<>", 9)
            ->where('programa.pro_id', "<>", 8)
            ->where('programa.pro_id', "<>", 11)
            ->select(
                'programa_inscripcion.pi_id',
                'programa_inscripcion.pi_doc_digital',
                'programa_inscripcion.pi_modulo',
                'programa_inscripcion.per_id',
                'programa_inscripcion.pro_id',
                'programa_inscripcion.pro_tur_id',
                'programa_inscripcion.sede_id',
                'programa_inscripcion.pi_certificacion',
                'programa_inscripcion.pi_observacion',
                'programa_inscripcion.created_at',
                'programa_turno.pro_tur_nombre',
                'map_persona.per_id',
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
                'programa_tipo.pro_tip_nombre',
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
                'programa_inscripcion.pi_certificacion',
                'programa_inscripcion.pi_observacion',
                'programa_inscripcion.created_at',
                'programa_turno.pro_tur_nombre',
                'map_persona.per_id',
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
                'programa_tipo.pro_tip_nombre',
                'sede.sede_nombre',
                'sede.sede_nombre_abre'
            )
            ->orderBy('map_persona.per_apellido1')
            ->orderBy('map_persona.per_apellido2');
        if (!is_null($this->user->pro_ids)) {
            $proIds = json_decode($this->user->pro_ids);
            if (!empty($proIds)) { // Verifica si $proIds no está vacío
                $inscripcion->whereIn('programa_inscripcion.pro_id', $proIds);
            }
        }
        if (!is_null($this->user->sede_ids)) {
            $sedeIds = json_decode($this->user->sede_ids);
            if (!empty($sedeIds)) { // Verifica si $proIds no está vacío
                $inscripcion->whereIn('programa_inscripcion.sede_id', $sedeIds);
            }
        }
        $inscripcion = $inscripcion->first(); 

        if ($inscripcion) {
            $personaNR = MapPersonaNr::where("per_id",$inscripcion->per_id)
            ->join('departamento as dep_nac', 'dep_nac.dep_id', '=', 'dep_nac_id')
            ->join('departamento as dep_res', 'dep_res.dep_id', '=', 'dep_res_id')
            ->select(
                'dep_nac.dep_nombre as dep_nac_nombre',
                'dep_res.dep_nombre as dep_res_nombre',
                'per_nac_provincia',
                'per_nac_municipio',
                'per_nac_localidad',
                'per_res_provincia',
                'per_res_municipio',
                'per_res_localidad',
                'per_res_direccion'
                )
            ->first();
            // Verificar si $personaNR es nulo y asignar valores predeterminados
            $dep_nac_nombre = $personaNR->dep_nac_nombre ?? 'No especificado';
            $dep_res_nombre = $personaNR->dep_res_nombre ?? 'No especificado';
            $per_nac_provincia = $personaNR->per_nac_provincia ?? 'No especificado';
            $per_nac_municipio = $personaNR->per_nac_municipio ?? 'No especificado';
            $per_nac_localidad = $personaNR->per_nac_localidad ?? 'No especificado';
            $per_res_provincia = $personaNR->per_res_provincia ?? 'No especificado';
            $per_res_municipio = $personaNR->per_res_municipio ?? 'No especificado';
            $per_res_localidad = $personaNR->per_res_localidad ?? 'No especificado';
            $per_res_direccion = $personaNR->per_res_direccion ?? 'No especificado';


            $actaConclusion = ActaConclusion::where("pi_id",$inscripcion->pi_id)->first();
            
            $calificacion = CalificacionParticipante::where("pc_id",8)->where("pi_id",$inscripcion->pi_id)->where('cp_estado','aprobado')->orderBy('pm_id')->get();
            $calificacion2 = CalificacionParticipante::where("pc_id",8)->where("pi_id",$inscripcion->pi_id)->orderBy('pm_id')->get();
            $totalPuntaje = $calificacion2->sum('cp_puntaje'); // Suma total de puntajes
            $promedio = $calificacion2->isNotEmpty() ? round($totalPuntaje / 5) : 0; // Promedio de puntajes
            $html = '';
            if($promedio >=70 and $inscripcion->total_deposito >= 1500 && $calificacion->count() >= 5){
                $html .= '
                <div class="card mb-4 shadow-lg border-light">
                    <div class="card-body">
                        <h4 class="font-weight-bold text-primary mb-4">Documentos Personales</h4>
                        <div class="row text-center">';
                        if($this->user->can('conclusionpago.pdf') && $inscripcion->total_deposito >= 1500){
                            $html .= '
                                <div class="col-md-2 mb-2">
                                    <a href="' . route('admin.inscripcion.participantepagopdf', encrypt($inscripcion->pi_id)) . '" 
                                    target="_blank" 
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center">
                                        <i class="icofont icofont-money"></i> Conclusión de Pago PDF
                                    </a>
                                </div>
                            ';
                        }
                        if($this->user->can('preinscripcion.pdf')){
                            $html .= '
                                <div class="col-md-2 mb-2">
                                    <a href="' . route('admin.inscripcion.formulariopdf', encrypt($inscripcion->pi_id)) . '" 
                                    target="_blank" 
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center">
                                        <i class="icofont icofont-law-document"></i> Preinscripción PDF
                                    </a>
                                </div>
                            ';
                        }
                        if($this->user->can('actaconclusion.pdf') && $actaConclusion ){
                            $html .= '
                                <div class="col-md-2 mb-2">
                                    <a href="' . route('admin.participante.actaconclusionpdf', encrypt($inscripcion->pi_id)) . '" 
                                    target="_blank" 
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center">
                                        <i class="icofont icofont-order"></i> Acta de Conclusión PDF
                                    </a>
                                </div>
                            ';
                        }
                        if($this->user->can('certificadonotas.pdf')){
                            $html.= '
                                <div class="col-md-2 mb-2">
                                    <a href="' . route('admin.participante.certificadonotanpdf', encrypt($inscripcion->pi_id)) . '" 
                                    target="_blank" 
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center">
                                        <i class="icofont icofont-clip-board"></i> Certificado de Notas PDF
                                    </a>
                                </div>
                            ';
                        }
                        if($this->user->can('registrouniversitaio.pdf')){
                            $html .= '
                                <div class="col-md-2 mb-2">
                                    <a href="' . route('admin.participante.registrouniversitariopdf', encrypt($inscripcion->pi_id)) . '" 
                                    target="_blank" 
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center">
                                        <i class="icofont icofont-user-alt-5"></i> Registro Universitario PDF
                                    </a>
                                </div>
                            ';
                        }
                        
                        $html .='</div>
                    </div>
                </div>';
                if ($this->user->can('certificacion.edit')) {
                    $html .= '
                    <div class="card mb-3 shadow-lg border-primary">
                        <div class="card-body">
                            <h4 class="font-weight-bold text-primary mb-4">Entrega de Certificado</h4>
                            <form method="POST" action="'.route('admin.guardar.certificado').'">
                                ' . csrf_field() . ' <!-- Token CSRF -->
                                <input type="hidden" name="pi_id" value="' . $inscripcion->pi_id . '">
                
                                <!-- Pregunta sobre certificación -->
                                <div class="form-group">
                                    <label for="pi_certificacion" class="font-weight-bold">¿SE CERTIFICÓ?</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="pi_certificacion" id="pi_certificacion" 
                                            value="1" ' . (old('pi_certificacion', $inscripcion ? $inscripcion->pi_certificacion : '') ? 'checked' : '') . '>
                                        <label class="custom-control-label" for="pi_certificacion">Sí, se certificó</label>
                                    </div>
                                </div>
                
                                <!-- Campo de observación que solo se muestra si no se certificó -->
                                <div class="form-group">
                                    <label for="pi_observacion" class="font-weight-bold">¿POR QUÉ NO SE CERTIFICÓ?</label>
                                    <input type="text" class="form-control shadow-sm" name="pi_observacion" id="pi_observacion" 
                                        value="' . old('pi_observacion', $inscripcion ? $inscripcion->pi_observacion : '') . '" 
                                        placeholder="Ingrese el motivo de no certificación">
                                </div>
                
                                <!-- Botón Guardar -->
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary shadow-sm">
                                        <i class="icofont-save"></i> Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>';
                }
                
                
                if ($this->user->can('actaconclusion.edit')) {
                    $html .= '
                    <div class="card mb-2 shadow-lg border-light">
                        <div class="card-body">
                            <h4 class="font-weight-bold text-primary mb-3">Acta de Conclusión</h4>
                            <form id="form-acta-conclusion" method="POST" action="' . route('admin.guardar.acta') . '">
                                ' . csrf_field() . ' <!-- Token CSRF -->
                                <input type="hidden" name="pi_id" value="' . $inscripcion->pi_id . '">
                                
                                <!-- Título del Proyecto -->
                                <div class="form-group">
                                    <label for="ac_titulo" class="font-weight-bold">TÍTULO DEL PRODUCTO ACADÉMICO FINAL</label>
                                    <input type="text" class="form-control shadow-sm" name="ac_titulo" id="ac_titulo" 
                                        value="' . old('ac_titulo', $actaConclusion ? $actaConclusion->ac_titulo : '') . '" 
                                        placeholder="Ingrese el título del producto" required>
                                </div>
                                
                                <!-- Puntuación del Producto Académico -->
                                <div class="form-group">
                                    <label for="ac_nota" class="font-weight-bold">PUNTAJE DEL PRODUCTO ACADÉMICO FINAL (sobre 100)</label>
                                    <input type="number" class="form-control shadow-sm" name="ac_nota" id="ac_nota" 
                                        value="' . old('ac_nota', $actaConclusion ? $actaConclusion->ac_nota : '') . '" 
                                        placeholder="Ingrese la puntuación" min="70" max="100" required>
                                </div>
                                
                                <!-- Descripción -->
                                <div class="form-group">
                                    <label for="ac_descripcion" class="font-weight-bold">DESCRIPCIÓN</label>
                                    <textarea class="form-control shadow-sm" name="ac_descripcion" id="ac_descripcion" rows="4" 
                                        placeholder="Ingrese una descripción" required>' 
                                        . old('ac_descripcion', $actaConclusion ? $actaConclusion->ac_descripcion : '') . '</textarea>
                                </div>
                                
                                <!-- Botón Guardar -->
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary shadow-sm">
                                        <i class="icofont-save"></i> Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>';
                }
                
                
            }else {
                $requisitosNoCumplidos = [];
                
                if ($promedio < 70) {
                    $requisitosNoCumplidos[] = 'Haber aprobado con un promedio mínimo de 70.';
                }
                if ($inscripcion->total_deposito < 1500) {
                    $requisitosNoCumplidos[] = 'Haber completado el pago total de 1500 Bs.';
                }
                if ($calificacion->count() < 5) {
                    $requisitosNoCumplidos[] = 'Haber aprobado hasta el módulo 5.';
                }
            
                $html .= '
                <div class="alert alert-warning">
                    <h6 class="font-weight-bold text-danger">No cumple con los requisitos correspondientes:</h6>
                    <ul>';
                foreach ($requisitosNoCumplidos as $requisito) {
                    $html .= '<li>' . $requisito . '</li>';
                }
                $html .= '
                    </ul>
                    <p>Por favor, revise los requisitos para generar el Acta de Conclusión.</p>
                </div>';
            }
            $html .= '
                <div class="card mb-2 shadow-lg border-light">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-primary mb-3">Calificación del Participante:</h6>';

                if ($calificacion2->isEmpty()) {
                    $html .= '<p>No se encontró calificación para este participante.</p>';
                } else {
                    $contador = 1; // Inicializamos el contador
                    $totalPuntaje = 0; // Inicializamos el total de puntajes

                    $html .= '<div class="row">'; // Comenzamos una fila para las calificaciones

                    foreach ($calificacion2 as $item) {
                        $html .= '
                        <div class="col-md-2 mb-2">
                            <p><strong>Módulo ' . $contador . ': ' . $item->pm_nombre . '</strong>
                                <span class="font-weight-bold" 
                                    style="font-size: 1.1em;
                                        padding: 5px 10px;
                                        border-radius: 5px;
                                        box-shadow: 0 4px 6px rgba(0, 128, 0, 0.2);
                                        background-color: ' . ($item->cp_puntaje < 70 ? '#f8d7da' : '#e8f8e9') . ';
                                        border: 1px solid ' . ($item->cp_puntaje < 70 ? '#f5c6cb' : '#28a745') . ';
                                        color: ' . ($item->cp_puntaje < 70 ? '#721c24' : '#28a745') . ';">
                                    ' . number_format($item->cp_puntaje, 2, ',', '.') . '
                                </span>
                            </p>
                        </div>';

                        $totalPuntaje += $item->cp_puntaje; // Sumar el puntaje al total
                        $contador++; // Aumentamos el contador para el siguiente módulo
                    }


                    // Agregar el promedio dentro de la misma fila
                    $html .= '
                        <div class="col-md-2 mb-2">
                            <p><strong>Promedio:</strong>
                                <span class="font-weight-bold" 
                                    style="font-size: 1.1em;
                                        padding: 5px 10px;
                                        border-radius: 5px;
                                        box-shadow: 0 4px 6px rgba(0, 128, 0, 0.2);
                                        background-color: #004085; /* Azul oscuro */
                                        border: 1px solid #003366; /* Azul más oscuro */
                                        color: #ffffff;"> <!-- Texto blanco para contraste -->
                                    ' . number_format($promedio, 2, ',', '.') . '
                                </span>
                            </p>
                        </div>';

                    $html .= '</div>'; // Cerramos la fila de calificaciones
                }

                $html .= '
                        </div>
            </div>';
            $html .= '
                <div class="card mb-4 shadow-lg border-light">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-primary mb-2">Datos Personales:</h6>
                        <div class="row">
                            <div class="col-md-5 mb-1">
                                <p><strong>Nombre Completo:</strong> 
                                    <span style="font-size: 1.2em; font-weight: bold; color: #2C3E50;">
                                        ' . $inscripcion->per_nombre1 . ' ' . $inscripcion->per_nombre2 . ' ' . $inscripcion->per_apellido1 . ' ' . $inscripcion->per_apellido2 . '
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-2 mb-1">
                                <p><strong>RDA:</strong> 
                                    <span style="font-size: 1.2em; font-weight: bold; color: #2C3E50;">
                                        ' . $inscripcion->per_rda . '
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-2 mb-1">
                                <p><strong>CI:</strong> 
                                    <span style="font-size: 1.2em; font-weight: bold; color: #2C3E50;">
                                        ' . $inscripcion->per_ci . 
                                        ($inscripcion->per_complemento ? '-' . $inscripcion->per_complemento : '') . '
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-2 mb-1">
                                <p><strong>Género:</strong> ' . $inscripcion->gen_nombre . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Fecha de Nacimiento:</strong> ' . $inscripcion->per_fecha_nacimiento . '</p>
                            </div>
                            <div class="col-md-4 mb-1">
                                <p>
                                    ' . ($inscripcion->per_celular == 0 ? 
                                        '<span class="alert alert-warning p-2">Celular: Debe llenar este campo.</span>' :
                                        '<span class="alert alert-success p-2">Celular: ' . $inscripcion->per_celular . '</span>') . 
                                '</p>
                            </div>

                            <div class="col-md-4 mb-1">
                                <p>
                                    ' . ($inscripcion->per_correo == 'sincorreo' ? 
                                        '<span class="alert alert-warning p-2">Correo: Debe llenar este campo.</span>' :
                                        '<span class="alert alert-success p-2">Correo: ' . $inscripcion->per_correo . '</span>') . 
                                '</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <h6 class="font-weight-bold text-primary mb-2">Datos de Inscripción:</h6>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <p><strong>Programa:</strong> ' .$inscripcion->pro_tip_nombre. ' en '. $inscripcion->pro_nombre . '</p>
                            </div>
                            <div class="col-md-6 mb-1">
                                <p><strong>Sede:</strong> ' . $inscripcion->sede_nombre . '</p>
                            </div>
                            <div class="col-md-6 mb-1">
                                <p><strong>Turno:</strong> ' . $inscripcion->pro_tur_nombre . '</p>
                            </div>
                            <div class="col-md-6 mb-1">
                                <p><strong>Total Depósito:</strong>
                                    <span class="font-weight-bold"
                                        style="font-size: 1.2em;
                                            padding: 5px 10px;
                                            border-radius: 5px;
                                            box-shadow: 0 4px 6px rgba(0, 128, 0, 0.2);
                                            background-color: ' . ($inscripcion->total_deposito < 1500 ? '#f8d7da' : '#e8f8e9') . ';
                                            border: 1px solid ' . ($inscripcion->total_deposito < 1500 ? '#f5c6cb' : '#28a745') . ';
                                            color: ' . ($inscripcion->total_deposito < 1500 ? '#721c24' : '#28a745') . ';">
                                        ' . number_format($inscripcion->total_deposito, 2, ',', '.') . ' Bs
                                    </span>
                                </p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <h6 class="font-weight-bold text-primary mb-2">Datos de Especialidad y Cargo:</h6>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <p><strong>Especialidad:</strong> ' . $inscripcion->esp_nombre . '</p>
                            </div>
                            <div class="col-md-6 mb-1">
                                <p><strong>Cargo:</strong> ' . $inscripcion->car_nombre . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Categoría:</strong> ' . $inscripcion->cat_nombre . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Nivel:</strong> ' . $inscripcion->niv_nombre . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Subsistema:</strong> ' . $inscripcion->sub_nombre . '</p>
                            </div>
                            <div class="col-md-2 mb-1">
                                <p><strong>En Función:</strong> 
                                    ' . ($inscripcion->per_en_funcion ? 
                                        '<span class="badge badge-success" style="font-size: 1.1em; padding: 8px 15px; background-color: #28a745; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 128, 0, 0.2);">Sí</span>' : 
                                        '<span class="badge badge-danger" style="font-size: 1.1em; padding: 8px 15px; background-color: #dc3545; border-radius: 15px; box-shadow: 0 4px 6px rgba(220, 53, 69, 0.2);">No</span>') . 
                                '</p>
                            </div>
                        </div>
                        <hr class="my-1">
                        <h6 class="font-weight-bold text-primary mb-2">Datos de Nacimiento</h6>
                        <div class="row">
                            <div class="col-md-3 mb-1">
                                <p><strong>Departamento:</strong> ' . $dep_nac_nombre . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Provincia:</strong> ' . $per_nac_provincia . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Municipio:</strong> ' . $per_nac_municipio . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Localidad:</strong> ' . $per_nac_localidad . '</p>
                            </div>
                        </div>
                        <hr class="my-1">
                        <h6 class="font-weight-bold text-primary mb-2">Datos de Residencia</h6>
                        <div class="row">
                            <div class="col-md-3 mb-1">
                                <p><strong>Departamento:</strong> ' . $dep_res_nombre . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Provincia:</strong> ' . $per_res_provincia . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Municipio:</strong> ' . $per_res_municipio . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Localidad:</strong> ' . $per_res_localidad . '</p>
                            </div>
                            <div class="col-md-3 mb-1">
                                <p><strong>Dirección:</strong> ' . $per_res_direccion . '</p>
                            </div>
                        </div>
                    </div>
                </div>';
            
            return response()->json($html);
        } else {
            return response()->json('<p class="text-danger font-weight-bold">No se encontraron resultados para el CI ingresado.</p>');
        }
    }
    public function guardarActa(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'pi_id' => 'required|exists:programa_inscripcion,pi_id',
            'ac_titulo' => 'required|string|max:255',
            'ac_nota' => 'required|integer|min:0,max:100',
            'ac_descripcion' => 'required|string',
        ]);

        try {
            // Convertir el título a mayúsculas
            $validated['ac_titulo'] = mb_strtoupper($validated['ac_titulo'], 'UTF-8');
            // Crear o actualizar el registro
            $acta = ActaConclusion::updateOrCreate(
                ['pi_id' => $validated['pi_id']],
                [
                    'ac_titulo' => $validated['ac_titulo'], 
                    'ac_nota' => $validated['ac_nota'],
                    'ac_descripcion' => $validated['ac_descripcion']
                ]
            );

            // Mensaje de éxito
            return redirect()
                ->route('admin.participantes.index')
                ->with('success', 'Acta de Conclusión guardada correctamente.');
        } catch (\Exception $e) {
            // Manejo de errores
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar el Acta de Conclusión: ' . $e->getMessage());
        }
    }
    public function guardarCertificado(Request $request)
    {
        $validated = $request->validate([
            'pi_id' => 'required|exists:programa_inscripcion,pi_id',
            'pi_certificacion' => 'nullable|boolean',
            'pi_observacion' => 'nullable|string|max:255',
        ]);
    
        try {
            // Buscar el registro por `pi_id`
            $inscripcion = ProgramaInscripcion::findOrFail($validated['pi_id']);

            // Actualizar los datos de la inscripción
            $inscripcion->pi_certificacion = $validated['pi_certificacion'] ?? 0; // Si no se envía, se asigna 0
            $inscripcion->pi_observacion = $validated['pi_observacion'] ?? null; // Se asigna null si está vacío
            $inscripcion->save();

            // Redireccionar con mensaje de éxito
            return redirect()
                ->route('admin.participantes.index')
                ->with('success', 'Certificado actualizado correctamente.');
        } catch (\Exception $e) {
            // Manejar errores y redireccionar con mensaje
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar el certificado: ' . $e->getMessage());
        }
    }


    public function filtrarInscripciones(Request $request)
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
            ->where('programa_inscripcion.pi_estado', "activo")
            ->where('programa_inscripcion.pie_id', 2)
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
            
            // Crear una pestaña para cada grupo
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
        
                foreach ($modulos as $index => $modulo) {
                    $calificacion = CalificacionParticipante::where('pi_id', $inscripcion->pi_id)
                        ->where('pm_id', $modulo->pm_id);

                        if($programaId == 8 || $programaId == 9){
                            $calificacion = $calificacion->where('pc_id', 9);
                        }else{
                            $calificacion = $calificacion->where('pc_id', 8);
                        }
                        
                    $calificacion = $calificacion->first();
        
                    $nota = $calificacion ? $calificacion->cp_puntaje : 'N/A';

                     // Determina la clase CSS para el color de la nota
                     $notaClass = '';
                     if ($nota === 0) {
                         $notaClass = 'bg-secondary text-white'; // Fondo oscuro para nota 0
                     } elseif ($nota < 70) {
                         $notaClass = 'bg-warning'; // Fondo amarillo para notas < 70
                     } elseif ($nota !== 'N/A') {
                         $notaClass = 'bg-success'; // Fondo verde para notas válidas
                     } else {
                         $notaClass = 'bg-danger'; // Fondo rojo si no aplica
                     }
                    
                    // Guarda el título del módulo con su calificación y clase CSS
                    $moduloTitulos[] = '<span class="badge ' . $notaClass . '"><strong>M ' . ($index + 1) . ':</strong> ' . ($nota !== 'N/A' ? $nota : 'N/A') . '</span>';

                    // Acumula notas para el promedio si es un valor numérico
                    if (is_numeric($nota)) {
                        $totalNotas += $nota;
                    }
                }
        
                // Calcular el promedio si hay calificaciones
                if($programaId == 8 || $programaId == 9){
                    $promedio = round($totalNotas / 3);
                } else {
                    $promedio = round($totalNotas / 5);
                }
        
                // Concatenar los módulos en una sola fila y agregar el promedio
                $moduloOutput = implode('  ', $moduloTitulos);
                $moduloOutput .= '  <span class="badge bg-primary"><strong>Prom:</strong> ' . ($promedio !== 'N/A' ? $promedio : 'N/A') . '</span>';
                $totalDepositoClass = '';
                if($programaId == 8 || $programaId == 9){
                    $totalDepositoClass = $inscripcion->total_deposito >= 150 ? '' : 'table-warning';
                }else{
                    $totalDepositoClass = $inscripcion->total_deposito >= 1500 ? '' : 'table-warning';
                }
                // Agregar el contenido al HTML de la tabla
                $acta_existe = ActaConclusion::where("pi_id","=", $inscripcion->pi_id)->exists();
                $mapPerNc = MapPersonaNr::where("per_id",$inscripcion->per_id)->exists();
                $tabContent .= '<tr>';
                $tabContent .= '<td>' . $rowIndex++ . '</td>';
                $color = $mapPerNc ? '' : ' class="bg-warning"';
                $tabContent .= '<td' . $color . '>' . $inscripcion->per_ci . ' ' . $inscripcion->per_complemento . '</td>';
                $tabContent .= '<td>' . $nombreCompleto . '</td>';
                $tabContent .= '<td class="' . $totalDepositoClass . '">Bs ' . number_format($inscripcion->total_deposito, 0, '.', '.') . '</td>';
                $tabContent .= '<td class=""><div class="text-center">' . $moduloOutput . '</div></td>';
                $tabContent .= '<td>
                                    <a class="btn btn-sm btn-info ver-detalles" data-id="' . $inscripcion->pi_id . '" data-bs-toggle="tooltip" title="Ver Detalles">
                                        <i class="icofont icofont-eye-alt"></i>
                                    </a>
                                    <a href="' . route('admin.inscripcion.formulariopdf', encrypt($inscripcion->pi_id)) . '"
                                        class="btn btn-warning btn-outline-warning waves-effect waves-light m-r-20">
                                        <i class="icofont icofont-files"></i>
                                    </a>
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
        if (is_null($this->user) || !$this->user->can('personaprograma.view')) {
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
            ->where('programa_inscripcion.pi_estado', "activo")
            ->where('programa_inscripcion.pie_id', 2)
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


    public function gereararActaConclusionpdf($pi_id){
        $pi_id = decrypt($pi_id);
        $inscripcion = DB::table('programa_inscripcion')
            ->select(
                DB::raw('CONCAT(UPPER(LEFT(map_persona.per_nombre1, 1)), LOWER(SUBSTRING(map_persona.per_nombre1, 2))) AS per_nombre1'),
                DB::raw('CONCAT(UPPER(LEFT(map_persona.per_nombre2, 1)), LOWER(SUBSTRING(map_persona.per_nombre2, 2))) AS per_nombre2'),
                DB::raw('CONCAT(UPPER(LEFT(map_persona.per_apellido1, 1)), LOWER(SUBSTRING(map_persona.per_apellido1, 2))) AS per_apellido1'),
                DB::raw('CONCAT(UPPER(LEFT(map_persona.per_apellido2, 1)), LOWER(SUBSTRING(map_persona.per_apellido2, 2))) AS per_apellido2'),
                DB::raw('UPPER(map_persona.per_ci) AS per_ci'),
                DB::raw('UPPER(map_persona.per_complemento) AS per_complemento'),
                DB::raw('UPPER(map_persona.per_rda) AS per_rda'),
                DB::raw('UPPER(map_persona.per_fecha_nacimiento) AS per_fecha_nacimiento'),
                DB::raw('UPPER(map_persona.per_celular) AS per_celular'),
                'map_persona.per_correo AS per_correo',
                DB::raw('UPPER(programa_tipo.pro_tip_nombre) AS pro_tip_nombre'),
                'programa.pro_id',
                DB::raw('UPPER(programa.pro_nombre) AS pro_nombre'),
                DB::raw('UPPER(programa.pro_costo) AS pro_costo'),
                DB::raw('UPPER(programa.pro_carga_horaria) AS pro_carga_horaria'),
                DB::raw('UPPER(programa.pro_fecha_inicio_clase) AS pro_fecha_inicio_clase'),
                DB::raw('UPPER(programa_duracion.pd_nombre) AS pd_nombre'),
                DB::raw('UPPER(departamento.dep_nombre) AS dep_nombre'),
                'sede.sede_id',
                'programa_tipo.pro_tip_id',
                'programa_modalidad.pm_id',
                DB::raw('UPPER(sede.sede_nombre) AS sede_nombre'),
                DB::raw('UPPER(programa_turno.pro_tur_nombre) AS pro_tur_nombre'),
                DB::raw('UPPER(programa_modalidad.pm_nombre) AS pm_nombre'),
                DB::raw('UPPER(programa_version.pv_nombre) AS pv_nombre'),
                DB::raw('UPPER(programa_version.pv_numero) AS pv_numero'),
                DB::raw('UPPER(programa_version.pv_gestion) AS pv_gestion'),
                DB::raw('UPPER(acta_conclusion.ac_titulo) AS ac_titulo'),
                DB::raw('UPPER(acta_conclusion.ac_descripcion) AS ac_descripcion'),
                DB::raw('UPPER(acta_conclusion.ac_nota) AS ac_nota'),
                DB::raw('UPPER(acta_conclusion.updated_at) AS updated_at_ac'),
                'programa_inscripcion.*'
            )
            ->join('programa_turno', 'programa_turno.pro_tur_id', '=', 'programa_inscripcion.pro_tur_id')
            ->join('map_persona', 'map_persona.per_id', '=', 'programa_inscripcion.per_id')
            ->join('programa', 'programa.pro_id', '=', 'programa_inscripcion.pro_id')
            ->join('programa_duracion', 'programa.pd_id', '=', 'programa_duracion.pd_id')
            ->join('sede', 'sede.sede_id', '=', 'programa_inscripcion.sede_id')
            ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
            ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
            ->join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
            ->join('programa_modalidad', 'programa_modalidad.pm_id', '=', 'programa.pm_id')
            ->join('acta_conclusion', 'acta_conclusion.pi_id', '=', 'programa_inscripcion.pi_id')
            // ->where('programa.pro_estado', '=', 2) //INSCRITO
            ->where('programa_inscripcion.pi_id', '=', $pi_id)
            ->first();
        $calificacion = CalificacionParticipante::where("pc_id",8)->where("pi_id",$pi_id)->orderBy('pm_id')->get();
        $totalPuntaje = $calificacion->sum('cp_puntaje'); // Suma total de puntajes
        $promedio = $calificacion->isNotEmpty() ? round($totalPuntaje / 5) : 0; // Promedio de puntajes


        $imagen3 = public_path() . "/img/logos/fondo.jpg";
        $fondo = base64_encode(file_get_contents($imagen3));
        $imagen5 = public_path() . "/assets/image/logoprofeiippminedu.png";
        $logo5 = base64_encode(file_get_contents($imagen5));



        $barcode = new DNS1D();
        $pi_id_formatted = str_pad($pi_id, 10, '0', STR_PAD_LEFT);
        // Concatenar los IDs formateados
        $dato = $pi_id_formatted;
        $dato_encrypt = $pi_id_formatted;
        $hash = md5('PROFE-'.$dato_encrypt);

        $exists = DB::table('barcode')->where('bar_md5', $hash)->where('bar_tipo','ACTA DE CONCLUSION')->exists();
        if (!$exists) {
            // Insertar si el hash no existe
            DB::table('barcode')->insert([
                'bar_md5' => $hash,
                'bar_descripcion' => $dato_encrypt,
                'bar_tipo' => 'ACTA DE CONCLUSION', // O el valor que desees
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }else{
            // Si el hash ya existe, actualizar solo la columna 'updated_at'
            DB::table('barcode')
                ->where('bar_md5', $hash)
                ->where('bar_tipo', 'ACTA DE CONCLUSION')
                ->update(['updated_at' => now()]);
        }
        $barcodeImage = $barcode->getBarcodePNG($dato, 'C128', 2.5, 60); 
        $codigoBarra = $hash;
        $pdf = Pdf::loadView(
            'backend/pages/acta_conclusion/partials/actaconclusion-participantePdf',
            compact('fondo', 'inscripcion', 'promedio',
            'logo5', 'barcodeImage', 'codigoBarra')
        );        
        return $pdf->download('acta-conclusion-' . $inscripcion->per_rda . '.pdf');
    }
    public function gereararCertificadoNotaspdf($pi_id){
        $pi_id = decrypt($pi_id);
        $inscripcion = DB::table('programa_inscripcion')
            ->select(
                DB::raw('UPPER(map_persona.per_nombre1) AS per_nombre1'),
                DB::raw('UPPER(map_persona.per_nombre2) AS per_nombre2'),
                DB::raw('UPPER(map_persona.per_apellido1) AS per_apellido1'),
                DB::raw('UPPER(map_persona.per_apellido2) AS per_apellido2'),
                DB::raw('UPPER(map_persona.per_ci) AS per_ci'),
                DB::raw('UPPER(map_persona.per_complemento) AS per_complemento'),
                DB::raw('UPPER(map_persona.per_rda) AS per_rda'),
                DB::raw('UPPER(map_persona.per_celular) AS per_celular'),
                'map_persona.per_correo AS per_correo',
                DB::raw('UPPER(programa_tipo.pro_tip_nombre) AS pro_tip_nombre'),
                'programa.pro_id',
                DB::raw('UPPER(programa.pro_nombre) AS pro_nombre'),
                DB::raw('UPPER(programa.pro_carga_horaria) AS pro_carga_horaria'),
                DB::raw('UPPER(programa.pro_fecha_inicio_clase) AS pro_fecha_inicio_clase'),
                DB::raw('UPPER(programa_duracion.pd_nombre) AS pd_nombre'),
                DB::raw('UPPER(departamento.dep_nombre) AS dep_nombre'),
                'sede.sede_id',
                'programa_tipo.pro_tip_id',
                'programa_modalidad.pm_id',
                DB::raw('UPPER(sede.sede_nombre) AS sede_nombre'),
                DB::raw('UPPER(programa_turno.pro_tur_nombre) AS pro_tur_nombre'),
                DB::raw('UPPER(programa_modalidad.pm_nombre) AS pm_nombre'),
                DB::raw('UPPER(programa_version.pv_nombre) AS pv_nombre'),
                DB::raw('UPPER(programa_version.pv_numero) AS pv_numero'),
                DB::raw('UPPER(programa_version.pv_gestion) AS pv_gestion'),
                'programa_inscripcion.*'
            )
            ->join('programa_turno', 'programa_turno.pro_tur_id', '=', 'programa_inscripcion.pro_tur_id')
            ->join('map_persona', 'map_persona.per_id', '=', 'programa_inscripcion.per_id')
            ->join('programa', 'programa.pro_id', '=', 'programa_inscripcion.pro_id')
            ->join('programa_duracion', 'programa.pd_id', '=', 'programa_duracion.pd_id')
            ->join('sede', 'sede.sede_id', '=', 'programa_inscripcion.sede_id')
            ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
            ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
            ->join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
            ->join('programa_modalidad', 'programa_modalidad.pm_id', '=', 'programa.pm_id')
            ->where('programa_inscripcion.pi_id', '=', $pi_id)
            ->first();
        $modulos = DB::table('calificacion_participante')
                    ->select(
                        DB::raw('UPPER(programa_inscripcion.pro_tur_id) AS pro_tur_id'),
                        DB::raw('UPPER(programa_modulo.pm_id) AS pm_id'),
                        DB::raw('UPPER(programa_modulo.pm_codigo) AS pm_codigo'),
                        DB::raw('UPPER(programa_modulo.pm_descripcion) AS pm_descripcion'),
                        DB::raw('UPPER(calificacion_participante.cp_puntaje) AS cp_puntaje'),
                        DB::raw('UPPER(calificacion_participante.cp_estado) AS cp_estado'),
                    )
                    ->join('programa_modulo', 'programa_modulo.pm_id', '=', 'calificacion_participante.pm_id')
                    ->join('programa_inscripcion', 'programa_inscripcion.pi_id', '=', 'calificacion_participante.pi_id')
                    ->where('calificacion_participante.pc_id', '=', 8)
                    ->where('calificacion_participante.pi_id', '=', $pi_id)
                    ->where('calificacion_participante.cp_estado', '=', "aprobado")->get();
        $barcode = new DNS1D();
        $pi_id_formatted = str_pad($pi_id, 10, '0', STR_PAD_LEFT);
        // Concatenar los IDs formateados
        $dato = $pi_id_formatted;
        $dato_encrypt = $pi_id_formatted;
        $hash = md5('PROFE-'.$dato_encrypt);

        $exists = DB::table('barcode')->where('bar_md5', $hash)->where('bar_tipo','CERTIFICADO NOTA')->exists();
        if (!$exists) {
            // Insertar si el hash no existe
            DB::table('barcode')->insert([
                'bar_md5' => $hash,
                'bar_descripcion' => $dato_encrypt,
                'bar_tipo' => 'CERTIFICADO NOTA', // O el valor que desees
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }else{
            // Si el hash ya existe, actualizar solo la columna 'updated_at'
            DB::table('barcode')
                ->where('bar_md5', $hash)
                ->where('bar_tipo', 'CERTIFICADO NOTA')
                ->update(['updated_at' => now()]);
        }
        $barcodeImage = $barcode->getBarcodePNG($dato, 'C128', 2.5, 60); 
        $codigoBarra = $hash;
        $datosQr = route('verificarCertificadoNota', ['bar' => $codigoBarra]);
        $qr = base64_encode(QrCode::format('svg')->size(180)->errorCorrection('H')->generate($datosQr));
        $user = $this->user;
        $pdf = Pdf::loadView(
            'backend/pages/acta_conclusion/partials/certificadonota-participantePdf',
            compact('inscripcion', 'modulos','qr','user')
        );        
        return $pdf->download('certificado-nota-' . $inscripcion->per_rda . '.pdf');
    }
    public function gereararRegistroUniversitariopdf($pi_id){
        $pi_id = decrypt($pi_id);
        $inscripcion = DB::table('programa_inscripcion')
            ->select(
                DB::raw('UPPER(map_persona.per_nombre1) AS per_nombre1'),
                DB::raw('UPPER(map_persona.per_nombre2) AS per_nombre2'),
                DB::raw('UPPER(map_persona.per_apellido1) AS per_apellido1'),
                DB::raw('UPPER(map_persona.per_apellido2) AS per_apellido2'),
                DB::raw('UPPER(map_persona.per_ci) AS per_ci'),
                DB::raw('UPPER(map_persona.per_complemento) AS per_complemento'),
                DB::raw('UPPER(map_persona.per_rda) AS per_rda'),
                DB::raw('UPPER(genero.gen_nombre) AS gen_nombre'),
                DB::raw('UPPER(map_persona.per_fecha_nacimiento) AS per_fecha_nacimiento'),
                DB::raw('UPPER(map_persona.per_celular) AS per_celular'),
                'map_persona.per_correo AS per_correo',
                DB::raw('UPPER(programa_tipo.pro_tip_nombre) AS pro_tip_nombre'),
                'programa.pro_id',
                DB::raw('UPPER(programa.pro_nombre) AS pro_nombre'),
                DB::raw('UPPER(programa.pro_carga_horaria) AS pro_carga_horaria'),
                DB::raw('UPPER(programa.pro_fecha_inicio_clase) AS pro_fecha_inicio_clase'),
                DB::raw('UPPER(programa_duracion.pd_nombre) AS pd_nombre'),
                DB::raw('UPPER(dep_nacimiento.dep_nombre) AS per_nac_departamento'),
                DB::raw('UPPER(map_persona_nr.per_nac_provincia) AS per_nac_provincia'),
                DB::raw('UPPER(map_persona_nr.per_nac_municipio) AS per_nac_municipio'),
                DB::raw('UPPER(map_persona_nr.per_nac_localidad) AS per_nac_localidad'),
                DB::raw('UPPER(dep_residencia.dep_nombre) AS per_res_departamento'),
                DB::raw('UPPER(map_persona_nr.per_res_provincia) AS per_res_provincia'),
                DB::raw('UPPER(map_persona_nr.per_res_municipio) AS per_res_municipio'),
                DB::raw('UPPER(map_persona_nr.per_res_localidad) AS per_res_localidad'),
                DB::raw('UPPER(map_persona_nr.per_res_direccion) AS per_res_direccion'),
                'sede.sede_id',
                'programa_tipo.pro_tip_id',
                'programa_modalidad.pm_id',
                DB::raw('UPPER(sede.sede_nombre) AS sede_nombre'),
                DB::raw('UPPER(dep_sede.dep_nombre) AS dep_nombre'),
                DB::raw('UPPER(programa_turno.pro_tur_nombre) AS pro_tur_nombre'),
                DB::raw('UPPER(programa_modalidad.pm_nombre) AS pm_nombre'),
                DB::raw('UPPER(programa_version.pv_nombre) AS pv_nombre'),
                DB::raw('UPPER(programa_version.pv_numero) AS pv_numero'),
                DB::raw('UPPER(programa_version.pv_gestion) AS pv_gestion'),
                'programa_inscripcion.*'
            )
            ->join('programa_turno', 'programa_turno.pro_tur_id', '=', 'programa_inscripcion.pro_tur_id')
            ->join('map_persona', 'map_persona.per_id', '=', 'programa_inscripcion.per_id')
            ->join('genero', 'genero.gen_id', '=', 'map_persona.gen_id')
            ->join('map_persona_nr', 'map_persona_nr.per_id', '=', 'map_persona.per_id')
            ->join('departamento as dep_nacimiento', 'dep_nacimiento.dep_id', '=', 'map_persona_nr.dep_nac_id')
            ->join('departamento as dep_residencia', 'dep_residencia.dep_id', '=', 'map_persona_nr.dep_res_id')
            ->join('programa', 'programa.pro_id', '=', 'programa_inscripcion.pro_id')
            ->join('programa_duracion', 'programa.pd_id', '=', 'programa_duracion.pd_id')
            ->join('sede', 'sede.sede_id', '=', 'programa_inscripcion.sede_id')
            ->join('departamento as dep_sede', 'dep_sede.dep_id', '=', 'sede.dep_id')
            ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
            ->join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
            ->join('programa_modalidad', 'programa_modalidad.pm_id', '=', 'programa.pm_id')
            ->where('programa_inscripcion.pi_id', '=', $pi_id)
            ->first();
        $barcode = new DNS1D();
        $pi_id_formatted = str_pad($pi_id, 10, '0', STR_PAD_LEFT);
        // Concatenar los IDs formateados
        $dato = $pi_id_formatted;
        $dato_encrypt = $pi_id_formatted;
        $hash = md5('PROFE-'.$dato_encrypt);

        $exists = DB::table('barcode')->where('bar_md5', $hash)->where('bar_tipo','REGISTRO UNIVERSITARIO')->exists();
        if (!$exists) {
            // Insertar si el hash no existe
            DB::table('barcode')->insert([
                'bar_md5' => $hash,
                'bar_descripcion' => $dato_encrypt,
                'bar_tipo' => 'REGISTRO UNIVERSITARIO', // O el valor que desees
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }else{
            // Si el hash ya existe, actualizar solo la columna 'updated_at'
            DB::table('barcode')
                ->where('bar_md5', $hash)
                ->where('bar_tipo', 'REGISTRO UNIVERSITARIO')
                ->update(['updated_at' => now()]);
        }
        $barcodeImage = $barcode->getBarcodePNG($dato, 'C128', 2.5, 60); 
        $codigoBarra = $hash;
        $datosQr = route('verificarRegistroUniversitario', ['bar' => $codigoBarra]);
        $qr = base64_encode(QrCode::format('svg')->size(180)->errorCorrection('H')->generate($datosQr));
        $user = $this->user;
        $pdf = Pdf::loadView(
            'backend/pages/acta_conclusion/partials/registrouniversitario-participantePdf',
            compact('inscripcion', 'qr','user')
        );        
        return $pdf->download('registro-universitario-' . $inscripcion->per_rda . '.pdf');
    }

    public function create(Request $request)
    {
       
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(string $pi_id)
    {
        

          }

    public function update(Request $request, string $id)
    {
        
    }
}
