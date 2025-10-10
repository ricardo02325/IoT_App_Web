<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;

    protected $table = 'lecturas';
    protected $primaryKey = 'id_lectura';
    public $timestamps = false;
    protected $fillable = [
        'id_sensor',
        'valor',
        'fecha_hora',
    ];
}