<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    use HasFactory;
    protected $table = 'barcode';

     // Definir la clave primaria si no es 'id'
     protected $primaryKey = 'bar_id';

     // Permitir la asignación masiva en estos campos
     protected $fillable = [
         'bar_md5',
         'bar_descrpcion',
         'bar_tipo',
     ];
}
