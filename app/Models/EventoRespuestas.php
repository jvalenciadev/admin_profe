<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoRespuestas extends Model
{
    use HasFactory;

    protected $table = 'evento_restriccion';
    protected $primaryKey = 'er_id';

    protected $fillable = [
        'er_descripcion',
        'gen_ids',
        'sub_ids',
        'niv_ids',
        'esp_ids',
        'esp_nombres',
        'cat_ids',
        'car_ids',
        'car_nombres',
        'pr_estado',
        'per_en_funcion',
        'eve_id',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'eve_id');
    }
}
