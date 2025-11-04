<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    protected $table = 'salones'; // nombre correcto
    protected $primaryKey = 'id_salon';
    public $timestamps = false;

    public function sensores()
    {
        return $this->hasMany(Sensor::class, 'id_salon', 'id_salon');
    }

    public function dispositivos()
    {
        return $this->hasMany(DispositivoParticle::class, 'id_salon', 'id_salon');
    }
}