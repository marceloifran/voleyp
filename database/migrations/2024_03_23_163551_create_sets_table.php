<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partido_id');
            $table->integer('numero');
            // Aquí añade las columnas para almacenar los datos específicos de cada set
            $table->integer('ataques');
            $table->integer('ataques_rojo');
            $table->integer('contrataques');
            $table->integer('contrataques_rojo');
            // Repite el mismo proceso para saques, bloqueos y recepciones...

            $table->timestamps();

            $table->foreign('partido_id')->references('id')->on('partidos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sets');
    }
};
