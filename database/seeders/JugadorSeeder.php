<?php

namespace Database\Seeders;

use App\Models\jugador;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JugadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        jugador::create([
            'nombre_completo' => 'Juan sosa' ,
            'posicion' => 'delantero',
            'categoria_id' => 1,
        ]);
    {
        jugador::create([
            'nombre_completo' => 'Juan Rojas',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'Juan perez',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'agustin pla',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'el bicho',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'di maria',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'cris ronaldo',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'dibu martinez',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'miguel suarez',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'marcelo sosa',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'nestor gomez',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'marcos soria',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
        jugador::create([
            'nombre_completo' => 'pepe soria',
            'posicion' => 'defensor',
            'categoria_id' => 1,
        ]);
    }
}

}
