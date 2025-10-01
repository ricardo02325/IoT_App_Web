<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'lecturas';

    // Si no tienes created_at / updated_at
    public $timestamps = false;

    // Columnas que se pueden llenar masivamente (opcional)
    protected $fillable = [
        'id', 'sensor', 'valor', 'fecha_hora' // ajusta según tus columnas
    ];
}