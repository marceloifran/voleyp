<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class entrenamiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'min_jugadores',
        'max_jugadores',
        'comentarios',
        'duracion',
        'materiales',
        'tecnicas',
        'configuracion_cancha'

    ];

    protected $casts = [
        'material' => 'json',
        'tecnicas' => 'json',
    ];
}
