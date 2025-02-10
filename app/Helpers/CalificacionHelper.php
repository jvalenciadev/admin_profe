<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CalificacionHelper
{
    public static function obtenerCalificacion($pm_id, $pi_id, $pc_id)
    {
        return DB::table('calificacion_participante')
            ->join('programa_calificacion', 'calificacion_participante.pc_id', '=', 'programa_calificacion.pc_id')
            ->join('programa_tipo_calificacion', 'programa_calificacion.ptc_id', '=', 'programa_tipo_calificacion.ptc_id')
            ->where('calificacion_participante.pm_id', $pm_id)
            ->where('calificacion_participante.pi_id', $pi_id)
            ->where('calificacion_participante.pc_id', $pc_id)
            ->first(); // Devuelve una sola calificación
    }
}