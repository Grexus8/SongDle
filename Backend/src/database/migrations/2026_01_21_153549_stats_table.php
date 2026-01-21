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
            Schema::create('estadisticas', function (Blueprint $table) {
            $table->id(); // Id
            $table->foreignId('id_usuario')
                  ->constrained('users')
                  ->onDelete('cascade'); // Id_Usuario
            $table->integer('partidas_jugadas')->default(0);
            $table->integer('partidas_ganadas')->default(0);
            $table->integer('racha')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
