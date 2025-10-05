<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_reporte';
    protected $table = 'reportes';
    protected $fillable = ['titulo', 'descripcion', 'id_salon'];

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon');
    }
}