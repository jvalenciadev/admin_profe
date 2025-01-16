<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoCuestionario extends Model
{
    use HasFactory;

    protected $table = 'evento_cuestionario';
    protected $primaryKey = 'eve_cue_id';

    protected $fillable = [
        'eve_cue_titulo',
        'eve_cue_descripcion',
        'eve_cue_fecha_ini',
        'eve_cue_fecha_fin',
        'eve_cue_estado',
        'eve_id'
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'eve_id', 'eve_id');
    }
}
