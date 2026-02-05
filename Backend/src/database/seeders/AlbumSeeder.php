<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Album;
use App\Models\Artist;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtenemos un mapa de [ 'Nombre Artista' => id_artista ]
        // Esto evita errores de IDs si borras y vuelves a crear la base de datos.
        $artists = Artist::pluck('id_artista', 'nombre');

        $albums = [];

        // Helper para verificar si el artista existe antes de intentar agregar álbumes
        // (Por si acaso no corriste el seeder anterior completo)
        
        // --- BAD BUNNY ---
        if (isset($artists['Bad Bunny'])) {
            $albums[] = [
                'nombre' => 'Un Verano Sin Ti',
                'id_artista' => $artists['Bad Bunny'],
                'fecha_lanzamiento' => '2022-05-06',
                'cantidad_canciones' => 23,
                'colaboraciones' => true,
                'premios' => 'Grammy, Latin Grammy',
                'reproducciones' => 14500000000, 
            ];
            $albums[] = [
                'nombre' => 'YHLQMDLG',
                'id_artista' => $artists['Bad Bunny'],
                'fecha_lanzamiento' => '2020-02-29',
                'cantidad_canciones' => 20,
                'colaboraciones' => true,
                'premios' => 'Grammy, Latin Grammy',
                'reproducciones' => 9000000000,
            ];
            $albums[] = [
                'nombre' => 'Nadie Sabe Lo Que Va A Pasar Mañana',
                'id_artista' => $artists['Bad Bunny'],
                'fecha_lanzamiento' => '2023-10-13',
                'cantidad_canciones' => 22,
                'colaboraciones' => true,
                'premios' => 'Billboard',
                'reproducciones' => 4000000000,
            ];
        }

        // --- KAROL G ---
        if (isset($artists['Karol G'])) {
            $albums[] = [
                'nombre' => 'Mañana Será Bonito',
                'id_artista' => $artists['Karol G'],
                'fecha_lanzamiento' => '2023-02-24',
                'cantidad_canciones' => 17,
                'colaboraciones' => true,
                'premios' => 'Grammy, Latin Grammy',
                'reproducciones' => 5000000000,
            ];
            $albums[] = [
                'nombre' => 'KG0516',
                'id_artista' => $artists['Karol G'],
                'fecha_lanzamiento' => '2021-03-26',
                'cantidad_canciones' => 16,
                'colaboraciones' => true,
                'premios' => 'Billboard Latin',
                'reproducciones' => 3500000000,
            ];
        }

        // --- FEID ---
        if (isset($artists['Feid'])) {
            $albums[] = [
                'nombre' => 'Feliz Cumpleaños Ferxxo Te Pirateamos El Álbum',
                'id_artista' => $artists['Feid'],
                'fecha_lanzamiento' => '2022-09-14',
                'cantidad_canciones' => 15,
                'colaboraciones' => true,
                'premios' => 'Nominado Latin Grammy',
                'reproducciones' => 3800000000,
            ];
            $albums[] = [
                'nombre' => 'MOR, No Le Temas a La Oscuridad',
                'id_artista' => $artists['Feid'],
                'fecha_lanzamiento' => '2023-09-29',
                'cantidad_canciones' => 15,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 1200000000,
            ];
        }

        // --- RAUW ALEJANDRO ---
        if (isset($artists['Rauw Alejandro'])) {
            $albums[] = [
                'nombre' => 'Saturno',
                'id_artista' => $artists['Rauw Alejandro'],
                'fecha_lanzamiento' => '2022-11-11',
                'cantidad_canciones' => 18,
                'colaboraciones' => true,
                'premios' => 'Nominado Grammy',
                'reproducciones' => 2500000000,
            ];
            $albums[] = [
                'nombre' => 'Vice Versa',
                'id_artista' => $artists['Rauw Alejandro'],
                'fecha_lanzamiento' => '2021-06-25',
                'cantidad_canciones' => 14,
                'colaboraciones' => true,
                'premios' => 'Latin Grammy',
                'reproducciones' => 4000000000,
            ];
            $albums[] = [
                'nombre' => 'Cosa Nuestra',
                'id_artista' => $artists['Rauw Alejandro'],
                'fecha_lanzamiento' => '2024-11-15',
                'cantidad_canciones' => 18,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 500000000,
            ];
        }

        // --- QUEVEDO ---
        if (isset($artists['Quevedo'])) {
            $albums[] = [
                'nombre' => 'Donde quiero estar',
                'id_artista' => $artists['Quevedo'],
                'fecha_lanzamiento' => '2023-01-20',
                'cantidad_canciones' => 16,
                'colaboraciones' => true,
                'premios' => 'Latin Grammy',
                'reproducciones' => 2800000000,
            ];
        }

        // --- ANUEL AA ---
        if (isset($artists['Anuel AA'])) {
            $albums[] = [
                'nombre' => 'Real Hasta la Muerte',
                'id_artista' => $artists['Anuel AA'],
                'fecha_lanzamiento' => '2018-07-17',
                'cantidad_canciones' => 12,
                'colaboraciones' => true,
                'premios' => 'Billboard Latin',
                'reproducciones' => 3000000000,
            ];
            $albums[] = [
                'nombre' => 'Emmanuel',
                'id_artista' => $artists['Anuel AA'],
                'fecha_lanzamiento' => '2020-05-29',
                'cantidad_canciones' => 22,
                'colaboraciones' => true,
                'premios' => 'Latin AMA',
                'reproducciones' => 2500000000,
            ];
        }

        // --- DUKI ---
        if (isset($artists['Duki'])) {
            $albums[] = [
                'nombre' => 'Ameri',
                'id_artista' => $artists['Duki'],
                'fecha_lanzamiento' => '2024-10-31',
                'cantidad_canciones' => 15,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 400000000,
            ];
            $albums[] = [
                'nombre' => 'Antes de Ameri',
                'id_artista' => $artists['Duki'],
                'fecha_lanzamiento' => '2023-06-22',
                'cantidad_canciones' => 14,
                'colaboraciones' => true,
                'premios' => 'Premios Gardel',
                'reproducciones' => 900000000,
            ];
        }

        // --- MYKE TOWERS ---
        if (isset($artists['Myke Towers'])) {
            $albums[] = [
                'nombre' => 'LA VIDA ES UNA',
                'id_artista' => $artists['Myke Towers'],
                'fecha_lanzamiento' => '2023-03-23',
                'cantidad_canciones' => 23,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 1800000000,
            ];
             $albums[] = [
                'nombre' => 'La Pantera Negra',
                'id_artista' => $artists['Myke Towers'],
                'fecha_lanzamiento' => '2024-08-22',
                'cantidad_canciones' => 20,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 300000000,
            ];
        }

        // --- JHAYCO ---
        if (isset($artists['Jhayco'])) {
            $albums[] = [
                'nombre' => 'Timelezz',
                'id_artista' => $artists['Jhayco'],
                'fecha_lanzamiento' => '2021-09-03',
                'cantidad_canciones' => 17,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 1500000000,
            ];
            $albums[] = [
                'nombre' => 'Le Clique: Vida Rockstar (X)',
                'id_artista' => $artists['Jhayco'],
                'fecha_lanzamiento' => '2024-09-06',
                'cantidad_canciones' => 29, // Versión completa
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 400000000,
            ];
        }

        // --- MORA ---
        if (isset($artists['Mora'])) {
            $albums[] = [
                'nombre' => 'Microdosis',
                'id_artista' => $artists['Mora'],
                'fecha_lanzamiento' => '2022-04-01',
                'cantidad_canciones' => 15,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 1600000000,
            ];
             $albums[] = [
                'nombre' => 'Estrella',
                'id_artista' => $artists['Mora'],
                'fecha_lanzamiento' => '2023-08-28',
                'cantidad_canciones' => 15,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 800000000,
            ];
        }
        
        // --- NICKI NICOLE ---
        if (isset($artists['Nicki Nicole'])) {
            $albums[] = [
                'nombre' => 'Alma',
                'id_artista' => $artists['Nicki Nicole'],
                'fecha_lanzamiento' => '2023-05-18',
                'cantidad_canciones' => 10,
                'colaboraciones' => true,
                'premios' => 'Nominado Latin Grammy',
                'reproducciones' => 600000000,
            ];
        }
        
        // --- ELADIO CARRION ---
        if (isset($artists['Eladio Carrión'])) {
            $albums[] = [
                'nombre' => '3MEN2 KBRN',
                'id_artista' => $artists['Eladio Carrión'],
                'fecha_lanzamiento' => '2023-03-17',
                'cantidad_canciones' => 18,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 900000000,
            ];
            $albums[] = [
                'nombre' => 'Sol María',
                'id_artista' => $artists['Eladio Carrión'],
                'fecha_lanzamiento' => '2024-01-19',
                'cantidad_canciones' => 17,
                'colaboraciones' => true,
                'premios' => '—',
                'reproducciones' => 400000000,
            ];
        }

        // Insertamos los datos
        // Usamos array_chunk por si la lista crece mucho en el futuro
        foreach (array_chunk($albums, 50) as $chunk) {
            Album::insert($chunk);
        }
    }
}