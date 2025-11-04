<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensores'; // nombre correcto
    protected $primaryKey = 'id_sensor';
    public $timestamps = false;

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon', 'id_salon');
    }

    public function lecturas()
    {
        return $this->hasMany(Lectura::class, 'id_sensor', 'id_sensor');
    }
}