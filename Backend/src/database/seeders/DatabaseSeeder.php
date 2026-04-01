<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ejecutar otros archivos de Seeder específicos
        $this->call([
            ArtistSeeder::class,
            AlbumSeeder::class,
            SongSeeder::class
        ]);
    }
}