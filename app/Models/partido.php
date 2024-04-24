<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class partido extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'rival',
        'set',
        'categoria_id',
       
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function sets()
    {
        return $this->hasMany(Set::class);
    }
}
