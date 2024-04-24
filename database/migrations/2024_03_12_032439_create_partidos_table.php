<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partido_id');
            $table->foreign('partido_id')->references('id')->on('partidos');
            $table->unsignedBigInteger('jugador_id');
            $table->foreign('jugador_id')->references('id')->on('jugadores');
            $table->integer('ataques')->default(0);
            $table->integer('ataques_rojo')->default(0);
            $table->integer('contrataques')->default(0);
            $table->integer('contrataques_rojo')->default(0);
            $table->integer('saques')->default(0);
            $table->integer('saques_rojo')->default(0);
            $table->integer('bloqueos')->default(0);
            $table->integer('bloqueos_rojo')->default(0);
            $table->json('recepciones');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sets');
    }
};
