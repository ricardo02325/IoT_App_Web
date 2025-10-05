<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_lectura';
    protected $table = 'lecturas';
    protected $fillable = ['id_sensor', 'valor', 'fecha_hora'];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'id_sensor');
    }
}