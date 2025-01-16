<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActaConclusion extends Model
{
    use HasFactory;
    // Definir el nombre de la tabla si es diferente a la convención estándar
    protected $table = 'acta_conclusion';

    // Definir la clave primaria si no es 'id'
    protected $primaryKey = 'ac_id';

    // Permitir la asignación masiva en estos campos
    protected $fillable = [
        'ac_titulo',
        'ac_descripcion',
        'ac_nota',
        'ac_documento',
        'pi_id',
    ];

    public function programaInscripcion()
    {
        return $this->belongsTo(ProgramaInscripcion::class, 'pi_id', 'pi_id');
    }
}
