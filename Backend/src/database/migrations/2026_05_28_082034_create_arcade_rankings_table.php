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
        Schema::create('arcade_rankings', function (Blueprint $table) {
            $table->id('id_ranking');
            
            $table->unsignedBigInteger('id_usuario')->unique();
            
            $table->integer('puntos_maximos')->default(0);
            $table->integer('canciones_adivinadas_max')->default(0);
            
            $table->timestamps();

            // Clave foránea para que si se borra el usuario, se borre su récord
            $table->foreign('id_usuario')->references('id_usuario')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arcade_rankings');
    }
};