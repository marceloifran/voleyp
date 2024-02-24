<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entrenamientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->integer('min_jugadores');
            $table->integer('max_jugadores');
            $table->integer('duracion');
            $table->json('materiales'); // Cambiado a tipo json
            $table->json('tecnicas'); // Cambiado a tipo json
            $table->json('configuracion_cancha')->nullable();
            $table->string('comentarios');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrenamientos');
    }
};
