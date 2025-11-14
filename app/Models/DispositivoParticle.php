<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispositivoParticle extends Model
{
    protected $table = 'dispositivos_particle';

    // Cambiado a la columna real de tu tabla
    protected $primaryKey = 'id';

    public $timestamps = true; // tu tabla tiene created_at y updated_at

    protected $fillable = [
        'id_salon',
        'device_id',
        'nombre',
        'estado', // solo si existe esta columna
    ];

    // Un dispositivo pertenece a un salón
    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon', 'id_salon');
    }

    // Sensores del dispositivo a través del salón
    public function sensores()
    {
        return $this->hasManyThrough(
            Sensor::class,  // Modelo destino
            Salon::class,   // Modelo intermedio
            'id_salon',     // Foreign key en Salon (referencia en DispositivoParticle)
            'id_salon',     // Foreign key en Sensor (referencia en Salon)
            'id_salon',     // Clave local en DispositivoParticle
            'id_salon'      // Clave local en Salon
        );
    }
}