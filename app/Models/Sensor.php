<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensores';
    protected $primaryKey = 'id_sensor';
    public $timestamps = false;

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon', 'id_salon');
    }

    // Relación con lecturas
    public function lecturas()
    {
        return $this->hasMany(Lectura::class, 'id_sensor', 'id_sensor');
    }

    // Última lectura
    public function ultimaLectura()
    {
        return $this->hasOne(Lectura::class, 'id_sensor', 'id_sensor')->latestOfMany('fecha_hora');
    }
}