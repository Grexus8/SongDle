<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Album;
use App\Models\Artist;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtenemos los artistas (Convertimos llaves a minúsculas para evitar errores de tipeo)
        $artists = Artist::all()->keyBy(function ($item) {
            return strtolower($item->nombre);
        });

        $albumsData = [];

        // --- DATOS DE LOS ÁLBUMES ---
        $data = [
            'Bad Bunny' => [
                ['nombre' => 'Un Verano Sin Ti', 'fecha' => '2022-05-06', 'canciones' => 23, 'colab' => true, 'premios' => 'Grammy, Latin Grammy', 'repro' => 14500000000],
                ['nombre' => 'YHLQMDLG', 'fecha' => '2020-02-29', 'canciones' => 20, 'colab' => true, 'premios' => 'Grammy, Latin Grammy', 'repro' => 9000000000],
                ['nombre' => 'Nadie Sabe Lo Que Va A Pasar Mañana', 'fecha' => '2023-10-13', 'canciones' => 22, 'colab' => true, 'premios' => 'Billboard', 'repro' => 4000000000],
            ],
            'Karol G' => [
                ['nombre' => 'Mañana Será Bonito', 'fecha' => '2023-02-24', 'canciones' => 17, 'colab' => true, 'premios' => 'Grammy, Latin Grammy', 'repro' => 5000000000],
                ['nombre' => 'KG0516', 'fecha' => '2021-03-26', 'canciones' => 16, 'colab' => true, 'premios' => 'Billboard Latin', 'repro' => 3500000000],
            ],
            'Feid' => [
                ['nombre' => 'Feliz Cumpleaños Ferxxo Te Pirateamos El Álbum', 'fecha' => '2022-09-14', 'canciones' => 15, 'colab' => true, 'premios' => 'Nominado Latin Grammy', 'repro' => 3800000000],
                ['nombre' => 'MOR, No Le Temas a La Oscuridad', 'fecha' => '2023-09-29', 'canciones' => 15, 'colab' => true, 'premios' => '—', 'repro' => 1200000000],
            ],
            'Rauw Alejandro' => [
                ['nombre' => 'Saturno', 'fecha' => '2022-11-11', 'canciones' => 18, 'colab' => true, 'premios' => 'Nominado Grammy', 'repro' => 2500000000],
                ['nombre' => 'Vice Versa', 'fecha' => '2021-06-25', 'canciones' => 14, 'colab' => true, 'premios' => 'Latin Grammy', 'repro' => 4000000000],
                ['nombre' => 'Cosa Nuestra', 'fecha' => '2024-11-15', 'canciones' => 18, 'colab' => true, 'premios' => '—', 'repro' => 500000000],
            ],
            'Quevedo' => [
                ['nombre' => 'Donde quiero estar', 'fecha' => '2023-01-20', 'canciones' => 16, 'colab' => true, 'premios' => 'Latin Grammy', 'repro' => 2800000000],
            ],
            'Anuel AA' => [
                ['nombre' => 'Real Hasta la Muerte', 'fecha' => '2018-07-17', 'canciones' => 12, 'colab' => true, 'premios' => 'Billboard Latin', 'repro' => 3000000000],
                ['nombre' => 'Emmanuel', 'fecha' => '2020-05-29', 'canciones' => 22, 'colab' => true, 'premios' => 'Latin AMA', 'repro' => 2500000000],
            ],
            'Duki' => [
                ['nombre' => 'Ameri', 'fecha' => '2024-10-31', 'canciones' => 15, 'colab' => true, 'premios' => '—', 'repro' => 400000000],
                ['nombre' => 'Antes de Ameri', 'fecha' => '2023-06-22', 'canciones' => 14, 'colab' => true, 'premios' => 'Premios Gardel', 'repro' => 900000000],
            ],
            'Myke Towers' => [
                ['nombre' => 'LA VIDA ES UNA', 'fecha' => '2023-03-23', 'canciones' => 23, 'colab' => true, 'premios' => '—', 'repro' => 1800000000],
                ['nombre' => 'La Pantera Negra', 'fecha' => '2024-08-22', 'canciones' => 20, 'colab' => true, 'premios' => '—', 'repro' => 300000000],
            ],
            'Jhayco' => [
                ['nombre' => 'Timelezz', 'fecha' => '2021-09-03', 'canciones' => 17, 'colab' => true, 'premios' => '—', 'repro' => 1500000000],
                ['nombre' => 'Le Clique: Vida Rockstar (X)', 'fecha' => '2024-09-06', 'canciones' => 29, 'colab' => true, 'premios' => '—', 'repro' => 400000000],
            ],
            'Mora' => [
                ['nombre' => 'Microdosis', 'fecha' => '2022-04-01', 'canciones' => 15, 'colab' => true, 'premios' => '—', 'repro' => 1600000000],
                ['nombre' => 'Estrella', 'fecha' => '2023-08-28', 'canciones' => 15, 'colab' => true, 'premios' => '—', 'repro' => 800000000],
            ],
            'Nicki Nicole' => [
                ['nombre' => 'Alma', 'fecha' => '2023-05-18', 'canciones' => 10, 'colab' => true, 'premios' => 'Nominado Latin Grammy', 'repro' => 600000000],
            ],
            'Eladio Carrión' => [
                ['nombre' => '3MEN2 KBRN', 'fecha' => '2023-03-17', 'canciones' => 18, 'colab' => true, 'premios' => '—', 'repro' => 900000000],
                ['nombre' => 'Sol María', 'fecha' => '2024-01-19', 'canciones' => 17, 'colab' => true, 'premios' => '—', 'repro' => 400000000],
            ],
        ];

        // 2. Procesamos e insertamos
        foreach ($data as $artistName => $artistAlbums) {
            $normalizedName = strtolower($artistName);
            
            if ($artists->has($normalizedName)) {
                $id_artista = $artists->get($normalizedName)->id_artista;
                
                foreach ($artistAlbums as $album) {
                    Album::create([
                        'nombre' => $album['nombre'],
                        'id_artista' => $id_artista,
                        'fecha_lanzamiento' => $album['fecha'],
                        'cantidad_canciones' => $album['canciones'],
                        'colaboraciones' => $album['colab'],
                        'premios' => $album['premios'],
                        'reproducciones' => $album['repro'],
                    ]);
                }
            } else {
                $this->command->warn("⚠️ Artista no encontrado: $artistName");
            }
        }

        $this->command->info("✅ Seeder de Álbumes completado.");
    }
}