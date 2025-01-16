<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoPersonas extends Model
{
    use HasFactory;

    protected $table = 'evento_personas';
    protected $primaryKey = 'eve_per_id';

    protected $fillable = [
        'eve_per_ci',
        'eve_per_complemento',
        'eve_per_expedido',
        'eve_per_nombre_1',
        'eve_per_nombre_2',
        'eve_per_apellido_1',
        'eve_per_apellido_2',
        'eve_per_fecha_nacimiento',
        'eve_per_correo',
        'eve_per_celular',
        'gen_id',
    ];
}
