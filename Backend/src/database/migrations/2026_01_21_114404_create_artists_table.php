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
    Schema::create('artists', function (Blueprint $table) {
        $table->id('id_artista');
        $table->string('nombre');
        $table->string('pais');
        $table->enum('genero', ['hombre', 'mujer']);
        $table->year('debut');
        $table->integer('cantidad_albumes')->default(0);
        $table->text('premios');
        $table->bigInteger('oyentes_mensuales')->default(0);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('artists');
    }
};
