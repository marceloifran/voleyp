<?php

namespace Database\Seeders;

use App\Models\categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        categoria::create([
            'id' => 1,
            'nombre' => 'Sub 18',
        ]);
        categoria::create([
            'id' => 2,

            'nombre' => 'Sub 21',
        ]);
        categoria::create([
            'id' => 3,

            'nombre' => 'Primera',
        ]);
    }
}
