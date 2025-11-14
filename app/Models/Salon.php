<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    protected $table = 'salones';
    protected $primaryKey = 'id_salon';
    protected $fillable = ['nombre', 'ubicacion', 'capacidad'];

    public $timestamps = false;

    // Relación con dispositivo Particle (1:1)
    public function dispositivoParticle()
    {
        return $this->hasOne(DispositivoParticle::class, 'id_salon', 'id_salon');
    }

    public function dispositivos()
    {
        return $this->hasMany(DispositivoParticle::class, 'id_salon', 'id_salon');
    }
}