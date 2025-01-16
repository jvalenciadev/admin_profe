<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoInscripcionV2 extends Model
{
    use HasFactory;

    protected $table = 'evento_inscripcion_v2';
    protected $primaryKey = 'eve_ins_id';

    protected $fillable = [
        'eve_ins_asistencia',
        'eve_ins_estado',
        'eve_per_id',
        'eve_id',
        'dep_id',
        'pm_id',
    ];

    public function eventoPersonas()
    {
        return $this->belongsTo(EventoPersonas::class, 'eve_per_id', 'eve_per_id');
    }
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'eve_id', 'eve_id');
    }
    public function departamento()
    {
        return $this->belongsTo(Evento::class, 'dep_id', 'dep_id');
    }
    public function programaModalidad()
    {
        return $this->belongsTo(Evento::class, 'pm_id', 'pm_id');
    }
    
}
