<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    protected $table = 'salones'; // <- Nombre correcto de la tabla
    protected $primaryKey = 'id_salon';
    public $timestamps = false;

    public function sensores()
    {
        return $this->hasMany(Sensor::class, 'id_salon', 'id_salon');
    }
}