<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPersonaNr extends Model
{
    use HasFactory;


    protected $table = 'map_persona_nr';

    // Definir la clave primaria correcta
    protected $primaryKey = 'per_nr_id';

    protected $fillable = [
        'per_nac_provincia',
        'per_res_provincia',
        'per_nac_municipio',
        'per_res_municipio',
        'per_nac_localidad',
        'per_res_localidad',
        'per_res_direccion',
        'per_id',
        'dep_nac_id',
        'dep_res_id'
    ];

    // Relación con el modelo ProgramaInscripcion
    public function mapPersona()
    {
        return $this->belongsTo(MapPersona::class, 'per_id', 'per_id');
    }
    // Relación con el modelo Departamento (nacimiento)
    public function departamentoNacimiento()
    {
        return $this->belongsTo(Departamento::class, 'dep_nac_id', 'dep_id');
    }

    // Relación con el modelo Departamento (residencia)
    public function departamentoResidencia()
    {
        return $this->belongsTo(Departamento::class, 'dep_res_id', 'dep_id');
    }
}
