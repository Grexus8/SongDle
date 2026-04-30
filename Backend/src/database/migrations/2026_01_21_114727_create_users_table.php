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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_usuario');

            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable(); // Añadido para el teléfono
            $table->string('password');
            $table->string('profile_img')->nullable(); // Añadido para guardar la imagen directamente
            $table->date('registration_date');
            $table->boolean('administrador')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};