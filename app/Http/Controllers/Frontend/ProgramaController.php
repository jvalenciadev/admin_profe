<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\MapPersona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Departamento;
use App\Models\Genero;
use App\Models\MapEspecialidad;
use App\Models\MapCargo;
use App\Models\Galeria;
use App\Models\MapNivel;
use App\Models\Admin;
use App\Models\MapSubsistema;
use App\Models\UnidadEducativa;
use App\Models\ProgramaRestriccion;
use App\Models\ProgramaBaucher;
use App\Models\ProgramaModalidad;
use App\Models\ProgramaSedeTurno;
use App\Models\Sede;
use App\Models\ProgramaTurno;
use App\Models\SolicitudInscripcionSede;
use App\Models\ProgramaInscripcion;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgramaController extends Controller
{
    // Programas
    public function index()
    {
        $programas = Programa::join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
                ->where('pro_estado', 'activo')
                ->orderBy('programa_tipo.pro_tip_nombre', 'ASC') // Ordenar por tipo
                ->orderBy('programa.pro_id', 'DESC') // Ordenar dentro del tipo
                ->get()
                ->groupBy('pro_tip_nombre'); // Agrupar por tipo
        return view('frontend.pages.programa.index', compact('programas'));
    }

    public function show($pro_id)
    {
        $programa = Programa::where('pro_estado', 'activo')->where('pro_id', $pro_id)->first();

        $galeriasPorPrograma = Galeria::selectRaw(
                    'galeria.*,
                    programa.pro_nombre_abre,
                    sede.sede_nombre_abre'
                )
                ->join('sede', 'galeria.sede_id', '=', 'sede.sede_id')
                ->join('programa', 'galeria.pro_id', '=', 'programa.pro_id')
                ->where('galeria.galeria_estado', 'activo')
                ->where('programa.pro_id', $pro_id)
                ->orderBy('galeria.updated_at', 'desc')
                ->get()
                ->groupBy('sede_id');
        return view('frontend.pages.programa.programa', compact('programa','galeriasPorPrograma'));
    }
    public function solicitarSedeInscripcion($pro_id){
        $programa = Programa::where('pro_id', $pro_id)->first();
        $restriccion = ProgramaRestriccion::where('pr_estado', 'activo')->where('pro_id', $pro_id)->first();
        $departamentos = Departamento::where('dep_estado', 'activo')->get();
        if($programa->pm_id !=3 && $programa->pro_tip_id == 2 ){
            return view('frontend.pages.programa.solicitarSedeInscripcion', compact('programa','departamentos','restriccion'));
        }
        else{
            return redirect('/ofertas-academicas')->with('error', 'Este programa no permite solicitar inscripciones a sedes.');
        }
    }
    public function solicitarSedePost(Request $request) {
        // Desencriptar el pro_id
        $pro_id = decrypt($request->input('pro_id'));

        // Crear la solicitud
        $solicitud = new SolicitudInscripcionSede();
        $solicitud->sis_ci = $request->input('sis_ci');
        $solicitud->sis_nombre_completo = $request->input('sis_nombre') . " " . $request->input('sis_apellido');
        $solicitud->sis_celular = $request->input('sis_celular');
        $solicitud->sis_correo = $request->input('sis_correo');
        $solicitud->sis_departamento = $request->input('sis_departamento');
        $solicitud->sis_sede = $request->input('sis_sede');
        $solicitud->sis_turno = $request->input('sis_turno');
        $solicitud->sis_estado = 'no confirmado';
        $solicitud->pro_id = $pro_id;

        // Guardar la solicitud
        $solicitud->save();

        // Redirigir al usuario con un mensaje de éxito
        return redirect('/ofertas-academicas')->with('success', 'La inscripción a la sede de su preferencia ha sido solicitada. Dentro de 48 horas se realizará la llamada para coordinar.');
    }
    public function inscripcion($pro_id){

        $user = Auth::guard('map_persona')->user();
        $per_ci = session('per_ci');
        $pro_id_session = session('pro_id');
        $programa = Programa::where('pro_estado', 'activo')->where('pro_id', $pro_id)->first();
        $proRestriccion = ProgramaRestriccion::where('pr_estado', 'activo')->where('pro_id', $pro_id)->first();

        if ($programa) {
            // Verificar si la fecha actual está dentro del rango de inscripción
            if (now()->between(
                Carbon::parse($programa->pro_fecha_inicio_inscripcion),
                Carbon::parse($programa->pro_fecha_fin_inscripcion)
            )) {
                // Si la inscripción está permitida, mostrar la vista de inscripción
                return view('frontend.pages.programa.inscripcion', compact('programa','proRestriccion'));
            } else {
                // Si la inscripción NO está permitida, redirigir con un mensaje
                return redirect('/ofertas-academicas')->with('error', 'La inscripción no está disponible en este momento.');
            }
        } else {
            // Si no se encuentra el programa, redirigir con un mensaje de error
            return redirect('/ofertas-academicas')->with('error', 'El programa solicitado no existe.');
        }
    }
    public function storeParticipante(Request $request)
    {
        $pro_id = decrypt($request['pro_id']);

        $request->validate([
            'per_ci' => [
                'required',
                'numeric',
                'regex:/^[0-9]{4,10}$/', // Solo números entre 4 y 10 dígitos
            ],
            'per_fecha_nacimiento' => [
               'required',
                'date',
                'before:today', // Fecha de nacimiento debe ser menor a la fecha actual
            ],
            'captcha' => 'required|captcha',
        ], [
            'per_ci.required' => 'El carnet de identidad es obligatorio.',
            'per_ci.numeric' => 'El carnet de identidad debe ser un número.',
            'per_ci.regex' => 'El carnet de identidad debe tener entre 4 y 10 dígitos.',
            'captcha.required' => 'El captcha es obligatorio.',
            'captcha.captcha' => 'El captcha es incorrecto.',
        ]);

        // Busca al usuario en la tabla MapPersona
        $usuario = MapPersona::join('map_especialidad', 'map_especialidad.esp_id', '=', 'map_persona.esp_id')
                        ->join('map_cargo', 'map_cargo.car_id', '=', 'map_persona.car_id')
                        ->join('map_subsistema', 'map_subsistema.sub_id', '=', 'map_persona.sub_id')
                        ->join('map_categoria', 'map_categoria.cat_id', '=', 'map_persona.cat_id')
                        ->where('map_persona.per_ci', $request->per_ci)
                        ->where('map_persona.per_fecha_nacimiento', $request->per_fecha_nacimiento)
                        ->first();
        $programa = Programa::where('pro_id', $pro_id)->first();


        // Si se encuentra el usuario en MapPersona
        if ($usuario) {
            if (!$usuario->per_en_funcion == 1) {
                return back()->withErrors(['error' => 'Usted no se encuentra en función.']);
            }
            $inscripcion = ProgramaInscripcion::join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
                ->join('programa', 'programa.pro_id', '=', 'programa_inscripcion.pro_id')
                ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
                ->where('map_persona.per_ci', $request->per_ci)
                ->where('programa.pro_codigo', $programa->pro_codigo)
                ->select('programa_inscripcion.*', 'map_persona.per_ci', 'programa.pro_codigo','programa_version.pv_gestion')
                ->first();
            $verinscripcion = ProgramaInscripcion::join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
                ->join('programa', 'programa.pro_id', '=', 'programa_inscripcion.pro_id')
                ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
                ->join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
                ->where('map_persona.per_ci', $request->per_ci)
                ->where('programa_tipo.pro_tip_id', $programa->pro_tip_id)
                ->where('programa_version.pv_gestion', (int)now()->year)
                ->where('programa_inscripcion.pro_id', '!=' , $programa->pro_id)
                ->first();
            if ($verinscripcion){
                return back()->withErrors(['error' => 'Usted ya se inscribió en otro diplomado de esta gestión. No puede inscribirse nuevamente.']);
            }
            // $admin = Admin::where('per_id',$usuario->per_rda)->where("estado",'activo')->first();
                // Verificar si la inscripción ya existe
            if ($inscripcion && (int)$inscripcion->pv_gestion !== (int)now()->year) {
                return back()->withErrors(['error' => 'Usted ya se inscribió anteriormente a la oferta formativa. No puede inscribirse nuevamente.']);
            }

            // if ($admin) {
            //     return back()->withErrors(['error'=> 'Usted es personal del programa PROFE, no puede realizar su inscripción.']);
            // }

            $restriccion = ProgramaRestriccion::where('pro_id',$pro_id)->first();
            if ($restriccion) {
                $subIds = json_decode($restriccion->sub_ids);
                $nivIds = json_decode($restriccion->niv_ids);
                $espIds = json_decode($restriccion->esp_ids);
                $espNombres = json_decode($restriccion->esp_nombres);
                $catIds = json_decode($restriccion->cat_ids);
                $carIds = json_decode($restriccion->car_ids);
                $carNombres = json_decode($restriccion->car_nombres);
                // dd($usuario);
                // Verificar restricciones para 'sub_ids'
                if ($subIds && !empty($subIds) && $subIds !== "null" && $subIds !== null && !in_array($usuario->sub_id, $subIds)) {

                    return back()->withErrors(['error' => 'El subsistema al que pertenece el usuario no está permitido para ' . $usuario->sub_nombre . '.']);
                }

                // Verificar restricciones para 'niv_ids'
                if ($nivIds && !empty($nivIds) && $nivIds !== "null" && $nivIds !== null && !in_array($usuario->niv_id, $nivIds)) {
                    return back()->withErrors(['error' => 'El nivel al que pertenece el usuario no está permitido para ' . $usuario->sub_nombre . '.']);
                }

                // Verificar restricciones para 'esp_ids'
                if ($espIds && !empty($espIds) && $espIds !== "null" && $espIds !== null && !in_array($usuario->esp_id, $espIds)) {
                    return back()->withErrors(['error' => 'La especialidad al que pertenece el usuario no está permitido para ' . ltrim($usuario->esp_nombre) . '.']);
                }

                // Verificar restricciones para 'esp_nombres'
                if ($espNombres && !empty($espNombres) && $espNombres !== "null" && $espNombres !== null) {
                    $espNombreValido = false;

                    // Recorrer cada valor en $espNombres y verificar si existe en $usuario->esp_nombre
                    foreach ($espNombres as $espNombre) {
                        if (stripos($usuario->esp_nombre, $espNombre) !== false) {
                            $espNombreValido = true;
                            break;
                        }
                    }

                    if (!$espNombreValido) {
                        return back()->withErrors(['error' => 'La especialidad al que pertenece el usuario no está permitido para ' . ltrim($usuario->esp_nombre) . '.']);
                    }
                }

                // Verificar restricciones para 'cat_ids'
                if ($catIds && !empty($catIds) && $catIds !== "null" && $catIds !== null && !in_array($usuario->cat_id, $catIds)) {
                    return back()->withErrors(['error' => 'La categoría al que pertenece el usuario no está permitido para ' . $usuario->cat_nombre . '.']);
                }
                // Verificar restricciones para 'cat_ids'
                if ($carIds && !empty($carIds) && $carIds !== "null" && $carIds !== null && !in_array($usuario->car_id, $carIds)) {
                    return back()->withErrors(['error' => 'El cargo al que pertenece el usuario no está permitido para ' . ltrim($usuario->car_nombre) . '.']);
                }

                // Verificar restricciones para 'car_nombres'
                if ($carNombres && !empty($carNombres) && $carNombres !== "null" && $carNombres !== null) {
                    $carNombreValido = false;

                    // Recorrer cada valor en $carNombres y verificar si existe en $usuario->car_nombre
                    foreach ($carNombres as $carNombre) {
                        if (stripos($usuario->car_nombre, $carNombre) !== false) {
                            $carNombreValido = true;
                            break;
                        }
                    }

                    if (!$carNombreValido) {
                        return back()->withErrors(['error' => 'El cargo al que pertenece el usuario no está permitido para ' . $usuario->car_nombre . '.']);
                    }
                }
            }

            // $restricion = ProgramaRestriccion::where();
            session([
                'pro_id' => $pro_id,
                'per_ci' => $request->per_ci,
            ]);
            // Autenticar usando el modelo MapPersona
            Auth::guard('map_persona')->login($usuario);
             session([
                'per_ci' => $usuario->per_ci,
                'per_complemento' => $usuario->per_complemento,
                'per_fecha_nacimiento' => $usuario->per_fecha_nacimiento,
                'per_nombre1' => $usuario->per_nombre1,
                'per_apellido1' => $usuario->per_apellido1,
                'per_apellido2' => $usuario->per_apellido2,
                'per_celular' => $usuario->per_celular,
                'per_correo' => $usuario->per_correo,
                'gen_id' => $usuario->gen_id,
            ]);
            // Redirigir al formulario de inscripción
            return redirect()->route('programa.inscribirse');
        }
        else {
            // Redirigir al formulario si no se encuentra el usuario
            return back()->withErrors([
                'error' => 'Usted no es personal del Sistema Educativo Plurinacional, no puede inscribirse. Comuníquese con la sede más cercana del programa PROFE.',
            ]);
        }
    }

    public function viewInscribirse()
    {
        // Obtiene el usuario autenticado y los datos de la sesión
        $user = Auth::guard('map_persona')->user();
        $per_ci = session('per_ci');
        $pro_id = session('pro_id');
        $departamentos = Departamento::all();
        $generos = Genero::all();
        // Verifica si el evento está activo
        $programa = Programa::join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')->where('pro_estado', 'activo')->where('pro_id', $pro_id)->first();
        $proRestriccion = ProgramaRestriccion::where('pr_estado', 'activo')->where('pro_id', $pro_id)->first();
        // $especialidad = MapEspecialidad::where('esp_estado', 'activo')->get();
        // $cargo = MapCargo::where('car_estado', 'activo')->get();
        // $uniEducativa = UnidadEducativa::where('uni_edu_estado', 'activo')->get();
        $nivel = MapNivel::where('niv_estado', 'activo')->get();
        $subsistema = MapSubsistema::where('sub_estado', 'activo')->get();

        // Si no se encuentra el evento, redirige a la página de eventos
        if (!$programa) {
            return redirect()->route('programa');
        }

        // Decodificar el campo JSON pm_ids a un array
        $pm_ids = json_decode($programa->pm_ids, true);

        // Filtrar las modalidades activas que estén en pm_ids
        $proSedeTur = ProgramaSedeTurno::join('sede', 'sede.sede_id', '=', 'programa_sede_turno.sede_id')
        ->where([
            ['pst_estado', '=', 'activo'],
            ['pro_id', '=', $pro_id],
        ])
        ->get();

        // Verifica si el usuario está autenticado o tiene CI en la sesión
        if ($user || $per_ci) {
            // Obtiene la información de la persona inscrita
            $pro_per = MapPersona::where('per_ci', $per_ci)->first();
            if($pro_per){
                // Verifica si ya está inscrito al programa
                $inscripcion = ProgramaInscripcion::where('per_id', $pro_per->per_id)
                ->where('pro_id', $pro_id)
                ->first();
                // Si ya está inscrito, redirige a la comprobación
                if ($inscripcion) {
                    return redirect()->route('programa.comprobanteParticipante', [
                        'per_id' => encrypt($pro_per->per_id),
                        'pro_id' => encrypt($pro_id),
                    ])->with('danger', 'Usted ya se encuentra registrado');
                }
                else{

                    return view('frontend.pages.programa.inscripcion_verificar', compact('departamentos','proRestriccion', 'generos', 'proSedeTur','nivel','subsistema', 'programa', 'user'));
                }
            }else{
                return view('frontend.pages.programa.inscripcion_verificar', compact('departamentos','proRestriccion', 'generos', 'proSedeTur','nivel','subsistema', 'programa', 'user'));
            }
        }
        // Retorna la vista de inscripcion_verificar con los datos requeridos
        return redirect('/ofertas-academicas')->with('error', 'El programa solicitado no existe.');
    }
    public function postInscribirse(Request $request)
    {


        try {
            // Desencriptar el ID del evento
            $pro_id = decrypt($request['pro_id']);

            // Buscar el usuario por su carnet de identidad y fecha de nacimiento
            $usuario = MapPersona::where('per_ci', $request['per_ci'])
                ->where('per_fecha_nacimiento', $request['per_fecha_nacimiento'])->first();
            if (!$usuario) {
                return back()->withErrors([
                    'per_ci' => 'No se encontró un usuario con los datos proporcionados.',
                ]);
            }

            // Actualizar los datos del usuario
            $usuario2 = $usuario->update([
                'per_celular' => $request['per_celular'] ?? '',
                'per_correo' => $request['per_correo'] ?? '',
            ]);
            $baucher = ProgramaBaucher::where('pro_bau_nro_deposito', $request['pro_bau_nro_deposito'])->exists();
            if ($baucher) {
                return back()->withErrors([
                    'per_ci' => 'El número de depósito ingresado ya ha sido utilizado. Para más detalles, le recomendamos acercarse a la sede correspondiente.',
                ]);
            }
            // Actualizar o crear el registro en ProgramaInscripcion
            $inscripcion = ProgramaInscripcion::updateOrCreate(
                [
                    'per_id' => $usuario->per_id,
                    'pro_id' => $pro_id,
                ],
                [
                    'per_id' => $usuario->per_id,
                    'pi_licenciatura' => $request['pi_licenciatura']??'',
                    'pi_unidad_educativa' => $request['pi_unidad_educativa']??'',
                    'pi_materia' => $request['pi_materia']??'',
                    'pi_nivel' => $request['pi_nivel']??'',
                    'pi_subsistema' => $request['pi_subsistema']??'',
                    'pro_tur_id' => $request['pro_tur_id'],
                    'sede_id' => $request['sede_id'],
                    'pro_id' => $pro_id,
                    'pie_id' => 1,
                ]
            );

            $programa_baucher = ProgramaBaucher::create([
                'pi_id' => $inscripcion->pi_id, // Asociar con la inscripción recién creada o actualizada
                'pro_bau_imagen' => $request['pro_bau_imagen'],
                'pro_bau_monto' => $request['pro_bau_monto'],
                'pro_bau_nro_deposito' => $request['pro_bau_nro_deposito'],
                'pro_bau_fecha' => $request['pro_bau_fecha'],
                'pro_bau_tipo_pago' => 'Baucher',
            ]);
            // Redirigir al comprobante
            return redirect()->route('programa.comprobanteParticipante', [
                'per_id' => encrypt($usuario->per_id),
                'pro_id' => encrypt($pro_id),
            ]);
        } catch (\Exception $e) {
            // Manejo de errores generales
            return back()->withErrors([
                'error' => 'Ocurrió un error durante el proceso: ' . $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        // Cierra la sesión del usuario
        auth()->logout();
        // Limpia todos los datos de la sesión
        session()->flush();

        \Log::info('Usuario ha cerrado sesión.');
        // Redirige al usuario a la página de inicio o donde desees
        return redirect('/ofertas-academicas')->with('success', 'Has cerrado sesión con éxito.');
    }
    public function comprobanteParticipante($per_id, $pro_id)
    {
        $data['programa'] = DB::table('programa')
            ->join('programa_inscripcion', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('map_persona', 'map_persona.per_id', '=', 'programa_inscripcion.per_id')
            ->join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
            ->where('map_persona.per_id', '=', decrypt($per_id))
            ->where('programa.pro_id', '=', decrypt($pro_id))
            ->first();


        return view('frontend.pages.programa.comprobanteParticipante', $data);
    }

    public function comprobanteParticipantePdf($per_id, $pro_id)
    {
        //
        $per_id = decrypt($per_id);
        $pro_id = decrypt($pro_id);
        //
        $participante = DB::table('programa_inscripcion')
            ->select(
                'programa_inscripcion.per_id',
                // 'evento_inscripcion.ei_rda',
                'map_persona.per_ci',
                'map_persona.per_complemento',
                'map_persona.per_nombre1',
                'map_persona.per_nombre2',
                'map_persona.per_apellido1',
                'map_persona.per_apellido2',
                'map_persona.per_celular',
                'map_persona.per_correo',
                'programa_inscripcion.pi_licenciatura',
                'programa_inscripcion.pi_unidad_educativa',
                'programa_inscripcion.pi_materia',
                // 'evento_inscripcion.ei_autorizacion',
                'programa.pro_id',
                'programa_inscripcion.created_at',
                'programa_inscripcion.updated_at',
                'programa_modalidad.pm_nombre',
                'programa_tipo.pro_tip_nombre',
                'programa_turno.pro_tur_nombre',
                'programa_version.pv_nombre',
                'programa_version.pv_romano',
                'programa_version.pv_romano',
                'programa_version.pv_gestion',
                'programa.pro_nombre',
                'programa_restriccion.res_descripcion',
                'departamento.dep_nombre',
                'sede.sede_nombre',
                DB::raw('(SELECT pro_bau_monto FROM programa_baucher WHERE programa_baucher.pi_id = programa_inscripcion.pi_id ORDER BY programa_baucher.pro_bau_fecha ASC LIMIT 1) as pro_bau_monto'),
                DB::raw('(SELECT pro_bau_nro_deposito FROM programa_baucher WHERE programa_baucher.pi_id = programa_inscripcion.pi_id ORDER BY programa_baucher.pro_bau_fecha ASC LIMIT 1) as pro_bau_nro_deposito'),
                DB::raw('(SELECT pro_bau_fecha FROM programa_baucher WHERE programa_baucher.pi_id = programa_inscripcion.pi_id ORDER BY programa_baucher.pro_bau_fecha ASC LIMIT 1) as pro_bau_fecha')
            )
            ->join('map_persona', 'map_persona.per_id', '=', 'programa_inscripcion.per_id')
            ->join('programa', 'programa.pro_id', '=', 'programa_inscripcion.pro_id')
            ->join('programa_turno', 'programa_turno.pro_tur_id', '=', 'programa_inscripcion.pro_tur_id')
            ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
            ->join('programa_modalidad', 'programa_modalidad.pm_id', '=', 'programa.pm_id')
            ->join('programa_restriccion', 'programa_restriccion.pro_id', '=', 'programa.pro_id')
            ->join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
            ->join('sede', 'sede.sede_id', '=', 'programa_inscripcion.sede_id')
            ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
            ->where('programa_inscripcion.per_id', '=', $per_id)
            ->where('programa_inscripcion.pro_id', '=', $pro_id)
            ->first();
        //
        // dd($participante);
        //
        $imagen1 = public_path() . "/assets/image/logoprofeiippminedu.png";
        $logo1 = base64_encode(file_get_contents($imagen1));


        $imagen3 = public_path() . "/img/iconos/alerta.png";
        $logo3 = base64_encode(file_get_contents($imagen3));

        $imagen3 = public_path() . "/img/logos/fondo.jpg";
        $fondo = base64_encode(file_get_contents($imagen3));

        // QR de encuesta
        $imagen4 = public_path() . "/img/qr/qrEncuesta.jpg";
        $qrEncuesta = base64_encode(file_get_contents($imagen4));
        //

        $datosQr = route('programa.comprobanteParticipantePdf', [
            'per_id' => encrypt($per_id),
            'pro_id' => encrypt($pro_id),
        ]);
        //
        $qr = base64_encode(QrCode::format('svg')->size(150)->errorCorrection('H')->generate($datosQr));
        $pdf = PDF::loadView('frontend.pages.programa.comprobantePdf', compact('participante', 'logo1', 'logo3', 'fondo', 'qr'));
        // VERTICAL
        $pdf->setPaper('Letter', 'portrait');
        // HORIZONTAL
        // $pdf->setPaper('Letter', 'landscape');
        //
        return $pdf->stream('FormularioInscripcion' . $participante->per_ci . '.pdf');
        // return $pdf->download('mi-archivo.pdf');
    }
    public function compromisoParticipantePdf($per_id, $pro_id)
    {
        //
        $per_id = decrypt($per_id);
        $pro_id = decrypt($pro_id);
        //
        $participante = DB::table('programa_inscripcion')
            ->select(
                'programa_inscripcion.per_id',
                // 'evento_inscripcion.ei_rda',
                'map_persona.per_ci',
                'map_persona.per_complemento',
                'map_persona.per_nombre1',
                'map_persona.per_nombre2',
                'map_persona.per_apellido1',
                'map_persona.per_apellido2',
                'map_persona.per_celular',
                'map_persona.per_correo',
                'programa_inscripcion.pi_licenciatura',
                'programa_inscripcion.pi_unidad_educativa',
                'programa_inscripcion.pi_materia',
                'programa_inscripcion.pi_nivel',
                'programa_inscripcion.pi_subsistema',
                // 'evento_inscripcion.ei_autorizacion',
                'programa.pro_id',
                'programa_inscripcion.created_at',
                'programa_inscripcion.updated_at',
                'programa_modalidad.pm_nombre',
                'programa_tipo.pro_tip_nombre',
                'programa_turno.pro_tur_nombre',
                'programa_version.pv_nombre',
                'programa_version.pv_romano',
                'programa_version.pv_romano',
                'programa_version.pv_gestion',
                'programa.pro_nombre',
                'departamento.dep_nombre',
                'sede.sede_nombre'
            )
            ->join('map_persona', 'map_persona.per_id', '=', 'programa_inscripcion.per_id')
            ->join('programa', 'programa.pro_id', '=', 'programa_inscripcion.pro_id')
            ->join('programa_turno', 'programa_turno.pro_tur_id', '=', 'programa_inscripcion.pro_tur_id')
            ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
            ->join('programa_modalidad', 'programa_modalidad.pm_id', '=', 'programa.pm_id')
            ->join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
            ->join('sede', 'sede.sede_id', '=', 'programa_inscripcion.sede_id')
            ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
            ->where('programa_inscripcion.per_id', '=', $per_id)
            ->where('programa_inscripcion.pro_id', '=', $pro_id)
            ->first();
        //
        // dd($participante);
        //
        $imagen1 = public_path() . "/assets/image/logoprofeiippminedu.png";
        $logo1 = base64_encode(file_get_contents($imagen1));


        $imagen3 = public_path() . "/img/iconos/alerta.png";
        $logo3 = base64_encode(file_get_contents($imagen3));
        $pdf = PDF::loadView('frontend.pages.programa.compromisoPdf', compact('participante', 'logo1', 'logo3'));
        $pdf->setPaper('Letter', 'portrait');
        return $pdf->stream('compromisoPDF_' . $participante->per_ci . '.pdf');
    }
    public function rotuloParticipantePdf($per_id, $pro_id)
    {
        //
        $per_id = decrypt($per_id);
        $pro_id = decrypt($pro_id);
        //
        $participante = DB::table('programa_inscripcion')
            ->select(
                'programa_inscripcion.per_id',
                // 'evento_inscripcion.ei_rda',
                'map_persona.per_ci',
                'map_persona.per_rda',
                'map_persona.per_complemento',
                'map_persona.per_nombre1',
                'map_persona.per_nombre2',
                'map_persona.per_apellido1',
                'map_persona.per_apellido2',
                'map_persona.per_celular',
                'map_persona.per_correo',
                'programa_inscripcion.pi_licenciatura',
                'programa_inscripcion.pi_unidad_educativa',
                'programa_inscripcion.pi_materia',
                // 'evento_inscripcion.ei_autorizacion',
                'programa.pro_id',
                'programa_inscripcion.created_at',
                'programa_inscripcion.updated_at',
                'programa_modalidad.pm_nombre',
                'programa_tipo.pro_tip_nombre',
                'programa_turno.pro_tur_nombre',
                'programa_version.pv_nombre',
                'programa_version.pv_romano',
                'programa_version.pv_romano',
                'programa_version.pv_gestion',
                'programa.pro_nombre',
                'departamento.dep_nombre',
                'sede.sede_nombre'
            )
            ->join('map_persona', 'map_persona.per_id', '=', 'programa_inscripcion.per_id')
            ->join('programa', 'programa.pro_id', '=', 'programa_inscripcion.pro_id')
            ->join('programa_turno', 'programa_turno.pro_tur_id', '=', 'programa_inscripcion.pro_tur_id')
            ->join('programa_version', 'programa_version.pv_id', '=', 'programa.pv_id')
            ->join('programa_modalidad', 'programa_modalidad.pm_id', '=', 'programa.pm_id')
            ->join('programa_tipo', 'programa_tipo.pro_tip_id', '=', 'programa.pro_tip_id')
            ->join('sede', 'sede.sede_id', '=', 'programa_inscripcion.sede_id')
            ->join('departamento', 'departamento.dep_id', '=', 'sede.dep_id')
            ->where('programa_inscripcion.per_id', '=', $per_id)
            ->where('programa_inscripcion.pro_id', '=', $pro_id)
            ->first();
        //
        // dd($participante);
        //
        $imagen1 = public_path() . "/assets/image/logoprofeiippminedu.png";
        $logo1 = base64_encode(file_get_contents($imagen1));


        $imagen3 = public_path() . "/img/iconos/alerta.png";
        $logo3 = base64_encode(file_get_contents($imagen3));
        $pdf = PDF::loadView('frontend.pages.programa.rotuloPdf', compact('participante', 'logo1', 'logo3'));
        $pdf->setPaper('Letter', 'portrait');
        return $pdf->stream('rotuloPDF_' . $participante->per_ci . '.pdf');
    }
    public function getSedes($dep_id, $pro_id)
    {
        $sedes = ProgramaSedeTurno::join('sede', 'programa_sede_turno.sede_id', '=', 'sede.sede_id')
            ->where('programa_sede_turno.pro_id', decrypt($pro_id))
            ->where('sede.dep_id', $dep_id)
            ->where('sede.sede_estado', 'activo')
            ->select('sede.sede_nombre','sede.sede_nombre_abre','sede.sede_id') // Selecciona todos los campos de `sedes`.
            ->get();

        return response()->json($sedes);
    }

    public function getTurnos($sede_id, $pro_id)
    {
        // Obtener la información de la sede y programa asociados
        $programaSedeTurno = ProgramaSedeTurno::where('sede_id', $sede_id)
            ->where('pro_id', decrypt($pro_id))
            ->first();

        // Inicializar un arreglo para los turnos
        $programaTurno = [];

        // Si se encontró el programa sede turno
        if ($programaSedeTurno) {
            // Decodificar los turnos en formato JSON
            $turnoIds = json_decode($programaSedeTurno->pro_tur_ids);

            // Obtener los turnos de la base de datos utilizando los IDs
            $programaTurno = ProgramaTurno::whereIn('pro_tur_id', $turnoIds)->get();
        }

        // Retornar la respuesta con los turnos
        return response()->json($programaTurno);
    }


}
