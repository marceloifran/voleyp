<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jugador extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'posicion',
        'categoria_id',
    ];

    public function categoria()
    {
        return $this->belongsTo(categoria::class);
    }
}
