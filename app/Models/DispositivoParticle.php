<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispositivoParticle extends Model
{
    protected $table = 'dispositivos_particle';
    protected $primaryKey = 'id_dispositivo';
    public $timestamps = true; // porque tu tabla tiene created_at y updated_at

    protected $fillable = [
        'id_salon',
        'device_id',
        'nombre_dispositivo',
        'estado',
    ];

    // Un dispositivo pertenece a un salón
    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon', 'id_salon');
    }

    // Un dispositivo tiene muchos sensores a través del salón
    public function sensores()
    {
        return $this->hasManyThrough(
            Sensor::class,   // Modelo destino
            Salon::class,    // Modelo intermedio
            'id_salon',      // Foreign key en Salon (referencia en DispositivoParticle)
            'id_salon',      // Foreign key en Sensor (referencia en Salon)
            'id_salon',      // Clave local en DispositivoParticle
            'id_salon'       // Clave local en Salon
        );
    }
}