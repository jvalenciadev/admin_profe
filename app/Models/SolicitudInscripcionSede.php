<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudInscripcionSede extends Model
{
    use HasFactory;

    protected $table = 'solicitud_inscripcion_sede';
    protected $primaryKey = 'sis_id';

    protected $fillable = [
        'sis_ci',
        'sis_nombre_completo',
        'sis_celular',
        'sis_correo',
        'sis_departamento',
        'sis_sede',
        'sis_turno',
        'sis_estado',
        'pro_id',
    ];
}
