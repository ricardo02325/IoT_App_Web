<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'correo',
        'contrasena',
        'tipo_usuario',
    ];

    protected $hidden = [
        'contrasena',
    ];

    // Scope para obtener solo alumnos
    public function scopeAlumnos($query)
    {
        return $query->where('tipo_usuario', 'alumno');
    }

    // Relación con salones
    public function salones()
    {
        return $this->belongsToMany(Salon::class, 'alumno_salon', 'id_usuario', 'id_salon');
    }
}