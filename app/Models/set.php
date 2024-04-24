<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class set extends Model
{
    protected $fillable = [
        'partido_id',
        'numero',
        'ataques',
        'contrataques',
        'saques',
        'bloqueos',
        // Agrega más atributos aquí según tu estructura de base de datos
    ];

    // Relación con el modelo Partido
    public function partido()
    {
        return $this->belongsTo(partido::class);
    }
}
