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
        $table->id();
        $table->string('name');
        $table->string('country');
        $table->year('debut');
        $table->integer('albums_count')->default(0);
        $table->integer('awards')->default(0);
        $table->bigInteger('monthly_listeners')->default(0);
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
