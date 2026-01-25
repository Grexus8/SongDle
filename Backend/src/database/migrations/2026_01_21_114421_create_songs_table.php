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
    Schema::create('songs', function (Blueprint $table) {
        $table->id('id_song');
        $table->string('titulo');
        $table->string('productor')->nullable();
        $table->string('pais');
        $table->year('anio');
        $table->string('genero');
        $table->bigInteger('reproducciones')->default(0);
        $table->unsignedBigInteger('id_artista');
        $table->unsignedBigInteger('id_album');
        
        
        $table->foreign('id_artista')->references('id_artista')->on('artists')->onDelete('cascade');
        $table->foreign('id_album')->references('id_album')->on('albums')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('songs');
    }
};
