<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sensor';
    protected $table = 'sensores';
    protected $fillable = ['id_tipo_sensor', 'id_salon', 'descripcion'];

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon');
    }

    public function lecturas()
    {
        return $this->hasMany(Lectura::class, 'id_sensor');
    }
}