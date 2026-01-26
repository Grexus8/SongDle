<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estadisticas', function (Blueprint $table) {
            $table->id();
            $table->integer('partidas_jugadas')->default(0);
            $table->integer('partidas_ganadas')->default(0);
            $table->integer('racha')->default(0);
            $table->unsignedBigInteger('id_usuario');
            
            $table->foreign('id_usuario') ->references('id_usuario')  ->on('users') ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadisticas');
    }
};