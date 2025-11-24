<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    protected $table = 'lecturas';
    protected $primaryKey = 'id_lectura';
    public $timestamps = false;

    // ⚡ Permitir asignación masiva
    protected $fillable = [
        'id_sensor',
        'valor',
        'fecha_hora', // opcional, si quieres registrar la fecha/hora al crear
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'id_sensor', 'id_sensor');
    }
}