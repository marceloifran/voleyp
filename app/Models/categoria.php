<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre'];

    public function jugadores()
    {
        return $this->hasMany(jugador::class);
    }

    public function partidos()
    {
        return $this->hasMany(partido::class);
    }
}
