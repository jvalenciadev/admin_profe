<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoPregunta extends Model
{
    use HasFactory;

    protected $table = 'evento_pregunta';
    protected $primaryKey = 'eve_pre_id';

    protected $fillable = [
        'eve_pre_texto',
        'eve_pre_tipo',
        'eve_pre_obligatorio',
        'eve_pre_respuesta_correcta',
        'eve_pre_estado',
        'eve_cue_id',
    ];

    public function eventoCuestionario()
    {
        return $this->belongsTo(EventoCuestionario::class, 'eve_cue_id');
    }
}
