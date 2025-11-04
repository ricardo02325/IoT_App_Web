<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispositivoParticle extends Model
{
    protected $table = 'dispositivos_particle';
    protected $primaryKey = 'id';
    public $timestamps = true; // porque tu tabla tiene created_at y updated_at

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon', 'id_salon');
    }

    public function sensores()
    {
        // Acceder a los sensores a través del salón
        return $this->hasManyThrough(
            Sensor::class,     // Modelo final
            Salon::class,      // Modelo intermedio
            'id_salon',        // FK del Salon en Sensor
            'id_salon',        // FK del DispositivoParticle en Salon
            'id_salon',        // Local key en DispositivoParticle
            'id_salon'         // Local key en Salon
        );
    }
}