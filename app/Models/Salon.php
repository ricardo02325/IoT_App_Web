<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    protected $table = 'salones';
    protected $primaryKey = 'id_salon';
    protected $fillable = ['nombre', 'ubicacion', 'capacidad'];

    public $timestamps = false; // 🚫 evita el error de updated_at

    public function dispositivos()
    {
        return $this->hasMany(DispositivoParticle::class, 'id_salon', 'id_salon');
    }
}