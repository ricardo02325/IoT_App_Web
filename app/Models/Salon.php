<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_salon';
    protected $table = 'salones';
    protected $fillable = ['nombre', 'ubicacion', 'capacidad'];

    public function alumnos()
    {
        return $this->belongsToMany(Usuario::class, 'alumno_salon', 'id_salon', 'id_usuario');
    }

    public function sensores()
    {
        return $this->hasMany(Sensor::class, 'id_salon');
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'id_salon');
    }
}