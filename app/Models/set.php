<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class set extends Model
{
    protected $fillable = ['partido_id', 'jugador_id', 'ataques', 'ataques_rojo', 'contrataques', 'contrataques_rojo', 'saques', 'saques_rojo', 'bloqueos', 'bloqueos_rojo', 'recepciones'];


    // Relación con el modelo Partido
    public function partido()
    {
        return $this->belongsTo(partido::class);
    }

    public function jugador()
    {
        return $this->belongsTo(jugador::class);
    }
}
