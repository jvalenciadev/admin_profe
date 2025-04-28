<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Genero;
use App\Models\EventoInscripcion;
use App\Models\Evento;
use App\Models\EventoInscripcionV2;
use App\Models\EventoPersonas;
use App\Models\MapPersona;
use App\Models\Blog;
use App\Models\ProgramaModalidad;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Milon\Barcode\DNS1D;



class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $user;
    public function index()
    {
        $departamento = Departamento::all();
        $genero = Genero::all();
        return view('frontend.pages.evento.index_1', [
            'departamentos' => $departamento,
            'generos' => $genero,
        ]);
    }

    // Eventos
    public function eventos()
    {
        $eventos = Evento::where('eve_estado', 'activo')
                ->orderBy('eve_fecha', 'DESC')
                ->paginate(9);
        $blogs = Blog::where('blog_estado', 'activo')->orderBy('blog_id', 'DESC')->get();

        return view('frontend.pages.evento.eventos', compact('eventos', 'blogs'));
    }
    public function detalle($eve_id)
    {
        $evento = Evento::where('eve_estado', 'activo')->where('eve_id', $eve_id)->first();
        $blogs = Blog::where('blog_estado', 'activo')->orderBy('blog_id', 'DESC')->get();

        return view('frontend.pages.evento.detalle', compact('evento', 'blogs'));
    }
    public function inscripcion($eve_id){

        $user = Auth::guard('map_persona')->user();
        $per_ci = session('per_ci');
        $eve_id_session = session('eve_id');
        $evento = Evento::where('eve_estado', 'activo')->where('eve_id', $eve_id)->first();

        if ($evento) {
            if ($evento->eve_inscripcion == 1) {
                // Si la inscripción está permitida (eve_inscripcion == 1)
                return view('frontend.pages.evento.inscripcion', compact('evento'));
            } else {
                // Si la inscripción NO está permitida (eve_inscripcion == 0), redirigir o mostrar un mensaje
                return redirect('/evento');
            }
        } else {
            // Si no se encuentra el evento o no está activo, redirigir con un mensaje de error
            return redirect('/evento');
        }
    }
    public function asistencia($eve_id){

        $user = Auth::guard('map_persona')->user();
        $per_ci = session('per_ci');
        $eve_id_session = session('eve_id');
        $evento = Evento::where('eve_estado', 'activo')->where('eve_id', $eve_id)->first();

        if ($evento) {
            if ($evento->eve_asistencia == 1) {
                return view('frontend.pages.evento.asistencia', compact('evento'));
            } else {
                return redirect('/evento');
            }
        } else {
            // Si no se encuentra el evento o no está activo, redirigir con un mensaje de error
            return redirect('/evento');
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

    }

    # PARTICIPANTES
    public function reloadCaptcha(){
        return response()->json(['captcha'=> captcha_img('mini')]);
    }


    public function storeParticipante(Request $request)
    {
        $eve_id = decrypt($request['eve_id']);

        $request->validate([
            'eve_per_ci' => [
                'required',
            ],
            'captcha' => 'required|captcha',
        ], [
            'eve_per_ci.required' => 'El carnet de identidad es obligatorio.',
            'eve_per_ci.numeric' => 'El carnet de identidad debe ser un número.',
            'eve_per_ci.regex' => 'El carnet de identidad debe tener entre 4 y 10 dígitos.',
            'captcha.required' => 'El captcha es obligatorio.',
            'captcha.captcha' => 'El captcha es incorrecto.',
        ]);

        // Busca al usuario en la tabla MapPersona
        $usuario = MapPersona::where('per_ci', $request->eve_per_ci)->first();
        // Busca si existe un registro en EventoPersonas
        $usuarioEvento = EventoPersonas::where('eve_per_ci', $request->eve_per_ci)->first();
        // Almacenar información en la sesión
        session([
            'eve_id' => $eve_id,
            'per_ci' => $request->eve_per_ci,
        ]);

        // Si se encuentra el usuario en MapPersona
        if ($usuario && $usuarioEvento) {
            // Autenticar usando el modelo MapPersona
            Auth::guard('map_persona')->login($usuario);
             session([
                'per_ci' => $usuarioEvento->eve_per_ci,
                'per_complemento' => $usuarioEvento->eve_per_complemento,
                'per_fecha_nacimiento' => $usuarioEvento->eve_per_fecha_nacimiento,
                'per_nombre1' => $usuarioEvento->eve_per_nombre_1,
                'per_apellido1' => $usuarioEvento->eve_per_apellido_1,
                'per_apellido2' => $usuarioEvento->eve_per_apellido_2,
                'per_celular' => $usuarioEvento->eve_per_celular,
                'per_correo' => $usuarioEvento->eve_per_correo,
                'gen_id' => $usuarioEvento->gen_id,
            ]);
            // Redirigir al formulario de inscripción
            return redirect()->route('evento.inscribirse');
        }
        elseif ($usuario) {
            // Autenticar usando el modelo MapPersona
            Auth::guard('map_persona')->login($usuario);
            // Redirigir al formulario de inscripción
            return redirect()->route('evento.inscribirse');
        }
        elseif($usuarioEvento) {

             // Actualiza la sesión con los datos de EventoPersonas
             session([
                'per_ci' => $usuarioEvento->eve_per_ci,
                'per_complemento' => $usuarioEvento->eve_per_complemento,
                'per_fecha_nacimiento' => $usuarioEvento->eve_per_fecha_nacimiento,
                'per_nombre1' => $usuarioEvento->eve_per_nombre_1,
                'per_apellido1' => $usuarioEvento->eve_per_apellido_1,
                'per_apellido2' => $usuarioEvento->eve_per_apellido_2,
                'per_celular' => $usuarioEvento->eve_per_celular,
                'per_correo' => $usuarioEvento->eve_per_correo,
                'gen_id' => $usuarioEvento->gen_id,
            ]);
            return redirect()->route('evento.inscribirse');
        }
        else {
            // Redirigir al formulario si no se encuentra el usuario
            return redirect()->route('evento.inscribirse')->withErrors([
                'eve_per_ci' => 'Debe registrar sus datos correctamente para su certificación. En caso contrario, no nos haremos responsables por errores en la emisión del certificado.',
            ]);
        }
    }
    public function storeAsistencia(Request $request)
    {
        $eve_id = decrypt($request['eve_id']);

        $request->validate([
            'eve_per_ci' => [
                'required',
                'numeric',
                'regex:/^[0-9]{4,10}$/', // Solo números entre 4 y 10 dígitos
            ],
            'eve_codigo' => [
                'required', // Solo números entre 4 y 10 dígitos
            ],
            'captcha' => 'required|captcha',
        ], [
            'eve_per_ci.required' => 'El carnet de identidad es obligatorio.',
            'eve_per_ci.numeric' => 'El carnet de identidad debe ser un número.',
            'eve_per_ci.regex' => 'El carnet de identidad debe tener entre 4 y 10 dígitos.',
            'eve_codigo.required' => 'El código del evento es obligatorio.',
            'captcha.required' => 'El captcha es obligatorio.',
            'captcha.captcha' => 'El captcha es incorrecto.',
        ]);

         // Busca si existe un registro en EventoPersonas
        $usuarioEvento = EventoPersonas::where('eve_per_ci', $request->eve_per_ci)->first();
        if (!$usuarioEvento) {
            return back()->withErrors(['asistencia' => 'Usted no se ha inscrito para confirmar su asistencia.']);
        }

        $verificarAsistencia = Evento::where('eve_id', $eve_id)->where('eve_codigo', $request->eve_codigo)->first();
        if (!$verificarAsistencia) {
            return back()->withErrors(['asistencia' => 'El código que introdujo no es válido para este evento.']);
        }

        $inscripcion = EventoInscripcionV2::whereBetween('eve_id', [38, 46])->where('eve_per_id', $usuarioEvento->eve_per_id)->first();
        if (!$inscripcion) {
            return back()->withErrors(['asistencia' => 'Usted no se ha inscrito para confirmar su asistencia.']);
        }

        // Verifica si ya se registró la asistencia
        if ($inscripcion->eve_ins_asistencia) {
            return back()->withErrors(['asistencia' => 'Usted ' . $usuarioEvento->eve_per_nombre_1 . ' ' . $usuarioEvento->eve_per_apellido_1 . ' ya está registrado su asistencia.']);
        } else {
            // Actualiza el estado de asistencia en EventoInscripcionV2
            $inscripcion->eve_ins_asistencia = true; // o 1
            $inscripcion->save();

            // Redirige con éxito
            return back()->withErrors(['asistencia' => 'Asistencia registrada exitosamente.']);
            // return back()->withErrors('asistencia', 'Asistencia registrada exitosamente.');
        }
    }
    /////////////////////////////////////////////////////////////
    public function viewInscribirse()
    {
        // Obtiene el usuario autenticado y los datos de la sesión
        $user = Auth::guard('map_persona')->user();
        $per_ci = session('per_ci');
        $eve_id = session('eve_id');
        $departamentos = Departamento::all();
        $generos = Genero::all();
        // Verifica si el evento está activo
        $evento = Evento::where('eve_estado', 'activo')->where('eve_id', $eve_id)->first();

        // Si no se encuentra el evento, redirige a la página de eventos
        if (!$evento) {
            return redirect()->route('evento');
        }

        // Decodificar el campo JSON pm_ids a un array
        $pm_ids = json_decode($evento->pm_ids, true);

        // Filtrar las modalidades activas que estén en pm_ids
        $modalidades = ProgramaModalidad::where('pm_estado', 'activo')
                                        ->whereIn('pm_id', $pm_ids)
                                        ->get();

        // Verifica si el usuario está autenticado o tiene CI en la sesión
        if ($user || $per_ci) {
            // Obtiene la información de la persona inscrita
            $eve_per = EventoPersonas::where('eve_per_ci', $per_ci)->first();
            if($eve_per){
                // Verifica si ya está inscrito al evento
                $inscripcion = EventoInscripcionV2::where('eve_per_id', $eve_per->eve_per_id)
                ->where('eve_id', $eve_id)
                ->first();
                // Si ya está inscrito, redirige a la comprobación
                if ($inscripcion) {
                    return redirect()->route('evento.comprobanteParticipante', [
                        'eve_per_id' => encrypt($eve_per->eve_per_id),
                        'eve_id' => encrypt($eve_id),
                    ])->with('danger', 'Usted ya cuenta con un registro, puede descargar su formulario de inscripción.');
                }
                else{

                    return view('frontend.pages.evento.inscripcion_verificar', compact('departamentos', 'generos', 'modalidades', 'evento', 'user'));
                }
            }else{
                return view('frontend.pages.evento.inscripcion_verificar', compact('departamentos', 'generos', 'modalidades', 'evento', 'user'));
            }
        }
        // Retorna la vista de inscripcion_verificar con los datos requeridos
        return view('frontend.pages.evento.inscripcion_verificar', compact('departamentos', 'generos', 'modalidades', 'evento', 'user'));
    }
    public function postInscribirse(Request $request)
    {
            // Desencriptar el ID del evento
        $eve_id = decrypt($request['eve_id']);

        // Validar todos los campos del formulario
        $request->validate([
                'eve_per_complemento' => 'nullable|max:5',
                'eve_per_fecha_nacimiento' => 'required|date|date_format:Y-m-d|before:today',
                'gen_id' => 'required',
                'eve_per_nombre_1' => 'required|string|max:150',
                'eve_per_apellido_1' => 'nullable|string|max:100',
                'eve_per_apellido_2' => 'nullable|string|max:100',
                'eve_per_celular' => [
                    'required',
                    'regex:/^6[0-9]{7}$|^7[0-9]{7}$/', // Comienza con 6 o 7 y tiene 8 dígitos
                ],
                'eve_per_correo' => 'required|email|max:100',
                'dep_id' => 'required',
            ], [
                'eve_per_complemento.max' => 'El complemento no puede tener más de 5 caracteres.',
                'eve_per_fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'eve_per_fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
                'eve_per_fecha_nacimiento.before' => 'La fecha de nacimiento debe estar en el pasado.',
                'gen_id.required' => 'El sexo es obligatorio.',
                'eve_per_nombre_1.required' => 'El nombre es obligatorio.',
                'eve_per_nombre_1.max' => 'El nombre no puede tener más de 150 caracteres.',
                'eve_per_apellido_1.max' => 'El apellido paterno no puede tener más de 100 caracteres.',
                'eve_per_apellido_2.max' => 'El apellido materno no puede tener más de 100 caracteres.',
                'eve_per_celular.required' => 'El número de celular es obligatorio.',
                'eve_per_celular.regex' => 'El número de celular debe comenzar con 6 o 7 y tener 8 dígitos.',
                'eve_per_correo.required' => 'El correo electrónico es obligatorio.',
                'eve_per_correo.email' => 'El formato del correo electrónico es inválido.',
                'eve_per_correo.max' => 'El correo electrónico no puede tener más de 100 caracteres.',
                'dep_id.required' => 'El departamento es obligatorio.',
            ]);

        // Buscar el usuario por su carnet de identidad
        $usuario = MapPersona::where('per_ci', $request->eve_per_ci)
            ->where('per_fecha_nacimiento', $request->eve_per_fecha_nacimiento)
            ->first();
        // Actualizar o crear el registro en EventoPersonas
        $usuario2 = EventoPersonas::updateOrCreate(
            ['eve_per_ci' => $request->eve_per_ci],
            [
                'eve_per_complemento' => $usuario->per_complemento ?? $request->eve_per_complemento ?? '',
                'eve_per_expedido' => '', // Aquí puedes ajustar según el campo expedido si lo tienes
                'eve_per_fecha_nacimiento' => $usuario->per_fecha_nacimiento ?? $request->eve_per_fecha_nacimiento,
                'gen_id' => $usuario->gen_id ?? decrypt($request->gen_id),
                'eve_per_nombre_1' => ($usuario->per_nombre1 ?? $request->eve_per_nombre_1) . ' ' . ($usuario->per_nombre2 ?? ''),
                'eve_per_nombre_2' => '', // Puedes agregar lógica si es necesario
                'eve_per_apellido_1' => $usuario->per_apellido1 ?? $request->eve_per_apellido_1 ?? '',
                'eve_per_apellido_2' => $usuario->per_apellido2 ?? $request->eve_per_apellido_2 ?? '',
                'eve_per_celular' => $request->eve_per_celular??'',
                'eve_per_correo' => $request->eve_per_correo??'',
            ]
        );
        // Crear o actualizar la inscripción
        $inscripcion = EventoInscripcionV2::updateOrCreate(
            ['eve_per_id' => $usuario2->eve_per_id, 'eve_id' => $eve_id],
            ['dep_id' => decrypt($request->dep_id), 'pm_id' => decrypt($request->pm_id)]
        );

        return redirect()->route('evento.comprobanteParticipante', [
            'eve_per_id' => encrypt($usuario2->eve_per_id),
            'eve_id' => encrypt($eve_id),
        ]);
    }
    public function logout(Request $request)
    {
        // Cierra la sesión del usuario
        auth()->logout();
        // Limpia todos los datos de la sesión
        session()->flush();

        \Log::info('Usuario ha cerrado sesión.');
        // Redirige al usuario a la página de inicio o donde desees
        return redirect('/evento')->with('success', 'Has cerrado sesión con éxito.');
    }



    /////////////////////////////////////////////////////////////
    public function comprobanteParticipante($eve_per_id, $eve_id)
    {
        $data['evento'] = DB::table('evento')
            ->join('evento_inscripcion_v2', 'evento_inscripcion_v2.eve_id', '=', 'evento.eve_id')
            ->join('evento_personas', 'evento_personas.eve_per_id', '=', 'evento_inscripcion_v2.eve_per_id')
            ->where('evento_inscripcion_v2.eve_per_id', '=', decrypt($eve_per_id))
            ->where('evento_inscripcion_v2.eve_id', '=', decrypt($eve_id))
            ->get();


        return view('frontend.pages.evento.comprobanteParticipante', $data);
    }

    public function comprobanteParticipantePdf($eve_per_id, $eve_id)
    {
        //
        $eve_per_id = decrypt($eve_per_id);
        $eve_id = decrypt($eve_id);
        //
        $participante = DB::table('evento_inscripcion_v2')
            ->select(
                'evento_personas.eve_per_id',
                // 'evento_inscripcion.ei_rda',
                'evento_personas.eve_per_ci',
                'evento_personas.eve_per_complemento',
                'evento_personas.eve_per_nombre_1',
                'evento_personas.eve_per_apellido_1',
                'evento_personas.eve_per_apellido_2',
                'evento_personas.eve_per_celular',
                'evento_personas.eve_per_correo',
                // 'evento_inscripcion.ei_autorizacion',
                'evento_inscripcion_v2.eve_id',
                'evento_personas.created_at',
                'programa_modalidad.pm_id',
                'programa_modalidad.pm_nombre',
                'evento.eve_nombre',
                'evento.eve_ins_hora_asis_habilitado',
                'evento.eve_ins_hora_asis_deshabilitado',
                'departamento.dep_nombre',
            )
            ->join('evento_personas', 'evento_personas.eve_per_id', '=', 'evento_inscripcion_v2.eve_per_id')
            ->join('evento', 'evento.eve_id', '=', 'evento_inscripcion_v2.eve_id')
            ->join('programa_modalidad', 'programa_modalidad.pm_id', '=', 'evento_inscripcion_v2.pm_id')
            ->join('departamento', 'departamento.dep_id', '=', 'evento_inscripcion_v2.dep_id')
            ->where('evento_inscripcion_v2.eve_per_id', '=', $eve_per_id)
            ->where('evento_inscripcion_v2.eve_id', '=', $eve_id)
            ->get();
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

        $datosQr = route('evento.comprobanteParticipantePdf', [
            'eve_per_id' => encrypt($eve_per_id),
            'eve_id' => encrypt($eve_id),
        ]);
        //
        $qr = base64_encode(QrCode::format('svg')->size(150)->errorCorrection('H')->generate($datosQr));
        $pdf = PDF::loadView('frontend.pages.evento.comprobantePdf', compact('participante', 'logo1', 'logo3', 'fondo', 'qr'));
        // VERTICAL
        $pdf->setPaper('Letter', 'portrait');
        // HORIZONTAL
        // $pdf->setPaper('Letter', 'landscape');
        //
        return $pdf->stream('comprobante' . $participante[0]->eve_per_ci . '.pdf');
        // return $pdf->download('mi-archivo.pdf');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
