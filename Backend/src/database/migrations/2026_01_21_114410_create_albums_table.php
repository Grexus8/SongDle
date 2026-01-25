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
    Schema::create('albums', function (Blueprint $table) {
        $table->id('id_album');
        $table->string('nombre');
        $table->date('fecha_lanzamiento');
        $table->integer('cantidad_canciones');
        $table->boolean('colaboraciones')->default(false);
        $table->integer('premios')->default(0);
        $table->bigInteger('reproducciones')->default(0);
        $table->unsignedBigInteger('id_artista');
        $table->foreign('id_artista') ->references('id_artista') ->on('artists') ->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('albums');
    }
};
