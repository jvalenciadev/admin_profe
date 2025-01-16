<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoOpciones extends Model
{
    use HasFactory;

    protected $table = 'evento_opciones';
    protected $primaryKey = 'eve_opc_id';

    protected $fillable = [
        'eve_opc_texto',
        'eve_opc_es_correcta',
        'eve_opc_estado',
        'eve_pre_id',
    ];

    public function eventoPregunta()
    {
        return $this->belongsTo(EventoPregunta::class, 'eve_pre_id', 'eve_pre_id');
    }
}
