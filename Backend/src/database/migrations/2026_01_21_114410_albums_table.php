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
        $table->id();
        $table->string('name');

        $table->foreignId('artist_id')
            ->constrained('artists')
            ->onDelete('cascade');

        $table->date('release_date');
        $table->integer('songs_count');
        $table->boolean('feats')->default(false);
        $table->integer('awards')->default(0);
        $table->bigInteger('reproductions')->default(0);

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
