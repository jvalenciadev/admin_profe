<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Añade esta línea para importar la clase Str
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Barcode;
use App\Models\ProgramaInscripcion;
use App\Models\CalificacionParticipante;
use Illuminate\Support\Facades\DB;

// Establecer el idioma a español
// Carbon::setLocale('es');
class VerificarBarcodeController extends Controller
{
    public function __construct()
    {
    }
    public function verificarRegistroAcademico($barcode)
    {
        $programa = Programa::where('pro_estado', 'activo')->where('pro_id', $pro_id)->first();
        return view('frontend.pages.programa.programa', compact('programa'));
    }
       
       
    public function verificarCertificadoPago(Request $barcode)
    {
        $barcode = Barcode::where('ba_md5', $barcode)->first();
        return view('frontend.pages.verificarBarcode.index', compact('barcode'));
    }
    public function verificarActaConclusion(Request $request)
    {

    }
    public function verificarRegistroUniversitario($bar)
    {
        $barcode = Barcode::where('bar_md5', $bar)->where('bar_tipo','REGISTRO UNIVERSITARIO')->first();
    
        // Si el código no existe
        if (!$barcode) {
            return view('frontend.pages.verificarBarcode.index', ['error' => 'Código no encontrado']);
        }
        $pi_id = $barcode->bar_descripcion;
        $pi_id_sin_ceros = ltrim($pi_id, '0'); 
    
        $participante = ProgramaInscripcion::join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_tipo', 'programa.pro_tip_id', '=', 'programa_tipo.pro_tip_id')
            ->join('programa_version', 'programa.pv_id', '=', 'programa_version.pv_id')
            ->where('programa_inscripcion.pi_id', $pi_id_sin_ceros)
            ->where('programa_inscripcion.pi_estado', "activo")
            ->where('programa_inscripcion.pie_id', 2)
            ->select(
                'map_persona.per_ci',
                'map_persona.per_complemento',
                'map_persona.per_nombre1',
                'map_persona.per_nombre2',
                'map_persona.per_apellido1',
                'map_persona.per_apellido2',
                'programa_version.*',
                'programa.pro_nombre',
                'programa_tipo.pro_tip_nombre',
            )->first();
    
       
    
        // Datos del participante y estado
        $tipo_barcode = "REGISTRO UNIVERSITARIO"; // Este dato debería venir de la base de datos.
    
        return view('frontend.pages.verificarBarcode.index', compact('barcode', 'tipo_barcode', 'participante'));
    }
    public function verificarCertificadoNota($bar)
    {
        $barcode = Barcode::where('bar_md5', $bar)->where('bar_tipo','CERTIFICADO NOTA')->first();
    
        // Si el código no existe
        if (!$barcode) {
            return view('frontend.pages.verificarBarcode.index', ['error' => 'Código no encontrado']);
        }
        $pi_id = $barcode->bar_descripcion;
        $pi_id_sin_ceros = ltrim($pi_id, '0'); 
    
        $participante = ProgramaInscripcion::join('map_persona', 'programa_inscripcion.per_id', '=', 'map_persona.per_id')
            ->join('programa', 'programa_inscripcion.pro_id', '=', 'programa.pro_id')
            ->join('programa_tipo', 'programa.pro_tip_id', '=', 'programa_tipo.pro_tip_id')
            ->join('programa_version', 'programa.pv_id', '=', 'programa_version.pv_id')
            ->where('programa_inscripcion.pi_id', $pi_id_sin_ceros)
            ->where('programa_inscripcion.pi_estado', "activo")
            ->where('programa_inscripcion.pie_id', 2)
            ->select(
                'map_persona.per_ci',
                'map_persona.per_complemento',
                'map_persona.per_nombre1',
                'map_persona.per_nombre2',
                'map_persona.per_apellido1',
                'map_persona.per_apellido2',
                'programa_version.*',
                'programa.pro_nombre',
                'programa_tipo.pro_tip_nombre',
            )->first();
    
        // Obtener los módulos
        $modulos = CalificacionParticipante::join('programa_modulo', 'calificacion_participante.pm_id', '=', 'programa_modulo.pm_id')
            ->where('pi_id', $pi_id_sin_ceros)
            ->where('pc_id', '8')
            ->get();
    
    
        // Datos del participante y estado
        $tipo_barcode = "CERTIFICADO DE NOTA"; // Este dato debería venir de la base de datos.
    
        return view('frontend.pages.verificarBarcode.index', compact('barcode', 'tipo_barcode', 'participante', 'modulos'));
    }
}
