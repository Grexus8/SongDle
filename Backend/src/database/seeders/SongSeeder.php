<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Song;
use App\Models\Artist;
use App\Models\Album;
use Carbon\Carbon;

class SongSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Mapeamos Nombres a IDs para no tener que poner números a mano
        $artists = Artist::pluck('id_artista', 'nombre'); // ['Bad Bunny' => 1, ...]
        $albums  = Album::pluck('id_album', 'nombre');   // ['Un Verano Sin Ti' => 5, ...]
        
        // Mapa auxiliar para el país del artista (para llenar el campo 'pais' de la canción)
        $artistCountries = Artist::pluck('pais', 'nombre');

        $catalog = [
            'Bad Bunny' => [
                ['t' => 'Tití Me Preguntó', 'a' => 'Un Verano Sin Ti', 'y' => 2022, 'p' => 'MAG', 'g' => 'Dembow', 'r' => 1300000000],
                ['t' => 'Me Porto Bonito', 'a' => 'Un Verano Sin Ti', 'y' => 2022, 'p' => 'MAG', 'g' => 'Reggaeton', 'r' => 1600000000],
                ['t' => 'Moscow Mule', 'a' => 'Un Verano Sin Ti', 'y' => 2022, 'p' => 'MAG', 'g' => 'Reggaeton', 'r' => 900000000],
                ['t' => 'Ojitos Lindos', 'a' => 'Un Verano Sin Ti', 'y' => 2022, 'p' => 'Tainy', 'g' => 'Pop Urbano', 'r' => 1100000000],
                ['t' => 'MONACO', 'a' => 'Nadie Sabe Lo Que Va A Pasar Mañana', 'y' => 2023, 'p' => 'MAG', 'g' => 'Trap', 'r' => 800000000],
                ['t' => 'PERRO NEGRO', 'a' => 'Nadie Sabe Lo Que Va A Pasar Mañana', 'y' => 2023, 'p' => 'MAG', 'g' => 'Reggaeton', 'r' => 750000000],
                ['t' => 'Safaera', 'a' => 'YHLQMDLG', 'y' => 2020, 'p' => 'Tainy', 'g' => 'Reggaeton', 'r' => 950000000],
                ['t' => 'Yo Perreo Sola', 'a' => 'YHLQMDLG', 'y' => 2020, 'p' => 'Subelo NEO', 'g' => 'Reggaeton', 'r' => 850000000],
                ['t' => 'Yonaguni', 'a' => null, 'y' => 2021, 'p' => 'Tainy', 'g' => 'Reggaeton', 'r' => 1500000000], // Single
                ['t' => 'Dákiti', 'a' => null, 'y' => 2020, 'p' => 'Jhayco', 'g' => 'Reggaeton', 'r' => 1800000000], // Single (parte de álbum ajeno o single promocional)
            ],
            'Karol G' => [
                ['t' => 'TQG', 'a' => 'Mañana Será Bonito', 'y' => 2023, 'p' => 'Ovy On The Drums', 'g' => 'Reggaeton', 'r' => 900000000],
                ['t' => 'Provenza', 'a' => 'Mañana Será Bonito', 'y' => 2022, 'p' => 'Ovy On The Drums', 'g' => 'Pop Urbano', 'r' => 1200000000],
                ['t' => 'Amargura', 'a' => 'Mañana Será Bonito', 'y' => 2023, 'p' => 'Ovy On The Drums', 'g' => 'Reggaeton', 'r' => 600000000],
                ['t' => 'MAMIII', 'a' => 'Mañana Será Bonito', 'y' => 2022, 'p' => 'Ovy On The Drums', 'g' => 'Reggaeton', 'r' => 850000000],
                ['t' => 'Bichota', 'a' => 'KG0516', 'y' => 2020, 'p' => 'Ovy On The Drums', 'g' => 'Reggaeton', 'r' => 1100000000],
                ['t' => 'Tusa', 'a' => 'KG0516', 'y' => 2019, 'p' => 'Ovy On The Drums', 'g' => 'Pop Urbano', 'r' => 1400000000],
                ['t' => 'El Makinon', 'a' => 'KG0516', 'y' => 2021, 'p' => 'Neo', 'g' => 'Reggaeton', 'r' => 700000000],
                ['t' => 'Si Antes Te Hubiera Conocido', 'a' => null, 'y' => 2024, 'p' => 'Coke', 'g' => 'Merengue', 'r' => 400000000], // Single reciente
                ['t' => 'Contigo', 'a' => null, 'y' => 2024, 'p' => 'Tiësto', 'g' => 'EDM Pop', 'r' => 200000000], // Single
                ['t' => 'Gatúbela', 'a' => null, 'y' => 2022, 'p' => 'DJ Maff', 'g' => 'Reggaeton', 'r' => 450000000], // Single
            ],
            'Feid' => [
                ['t' => 'Normal', 'a' => 'Feliz Cumpleaños Ferxxo Te Pirateamos El Álbum', 'y' => 2022, 'p' => 'Sky Rompiendo', 'g' => 'Reggaeton', 'r' => 600000000],
                ['t' => 'Feliz Cumpleaños Ferxxo', 'a' => 'Feliz Cumpleaños Ferxxo Te Pirateamos El Álbum', 'y' => 2022, 'p' => 'Sky Rompiendo', 'g' => 'Reggaeton', 'r' => 700000000],
                ['t' => 'Hey Mor', 'a' => 'Feliz Cumpleaños Ferxxo Te Pirateamos El Álbum', 'y' => 2022, 'p' => 'Sky Rompiendo', 'g' => 'Reggaeton', 'r' => 900000000],
                ['t' => 'Prohibidox', 'a' => 'Feliz Cumpleaños Ferxxo Te Pirateamos El Álbum', 'y' => 2022, 'p' => 'Sky Rompiendo', 'g' => 'Reggaeton', 'r' => 300000000],
                ['t' => 'Classy 101', 'a' => null, 'y' => 2023, 'p' => 'Caleb Calloway', 'g' => 'Reggaeton', 'r' => 1000000000], // Single (luego añadido a álbum pero funciona como single)
                ['t' => 'Luna', 'a' => 'MOR, No Le Temas a La Oscuridad', 'y' => 2023, 'p' => 'Sky Rompiendo', 'g' => 'Reggaeton', 'r' => 800000000],
                ['t' => 'Bubalu', 'a' => 'MOR, No Le Temas a La Oscuridad', 'y' => 2023, 'p' => 'Sky Rompiendo', 'g' => 'Afrobeat', 'r' => 250000000],
                ['t' => 'Chorrito pa las animas', 'a' => null, 'y' => 2022, 'p' => 'Sky Rompiendo', 'g' => 'Reggaeton', 'r' => 500000000], // Single
                ['t' => 'Si Te La Encuentras Por Ahí', 'a' => null, 'y' => 2022, 'p' => 'Jowan', 'g' => 'Reggaeton', 'r' => 400000000], // Single
                ['t' => 'Perro Negro', 'a' => null, 'y' => 2023, 'p' => 'MAG', 'g' => 'Reggaeton', 'r' => 750000000], // Ft Bad Bunny
            ],
            'Rauw Alejandro' => [
                ['t' => 'Todo de Ti', 'a' => 'Vice Versa', 'y' => 2021, 'p' => 'Mr. NaisGai', 'g' => 'Synth Pop', 'r' => 1500000000],
                ['t' => 'Desesperados', 'a' => 'Vice Versa', 'y' => 2021, 'p' => 'Caleb Calloway', 'g' => 'Reggaeton', 'r' => 900000000],
                ['t' => '2/Catorce', 'a' => 'Vice Versa', 'y' => 2021, 'p' => 'Mr. NaisGai', 'g' => 'R&B', 'r' => 600000000],
                ['t' => 'Lokera', 'a' => 'Saturno', 'y' => 2022, 'p' => 'Mr. NaisGai', 'g' => 'Reggaeton', 'r' => 700000000],
                ['t' => 'Punto 40', 'a' => 'Saturno', 'y' => 2022, 'p' => 'Rauw', 'g' => 'Reggaeton', 'r' => 500000000],
                ['t' => 'Ron Cola', 'a' => 'Saturno', 'y' => 2022, 'p' => 'Rauw', 'g' => 'Reggaeton', 'r' => 300000000],
                ['t' => 'Diluvio', 'a' => 'Saturno', 'y' => 2022, 'p' => 'Caleb Calloway', 'g' => 'Synth Pop', 'r' => 250000000],
                ['t' => 'Santa', 'a' => 'Cosa Nuestra', 'y' => 2024, 'p' => 'Rvssian', 'g' => 'Reggaeton', 'r' => 400000000],
                ['t' => 'Te Felicito', 'a' => null, 'y' => 2022, 'p' => 'Shakira', 'g' => 'Pop Urbano', 'r' => 800000000], // Single
                ['t' => 'Beso', 'a' => null, 'y' => 2023, 'p' => 'Rosalía', 'g' => 'Reggaeton', 'r' => 700000000], // EP RR (Single)
            ],
            'Jhayco' => [
                ['t' => 'Dákiti', 'a' => 'Timelezz', 'y' => 2020, 'p' => 'Tainy', 'g' => 'Reggaeton', 'r' => 1800000000],
                ['t' => 'Ley Seca', 'a' => 'Timelezz', 'y' => 2021, 'p' => 'Anuel AA', 'g' => 'Reggaeton', 'r' => 600000000],
                ['t' => 'No Me Conoce (Remix)', 'a' => null, 'y' => 2019, 'p' => 'Mvsis', 'g' => 'Reggaeton', 'r' => 1200000000], // Single
                ['t' => 'Holanda', 'a' => null, 'y' => 2023, 'p' => 'Haze', 'g' => 'Reggaeton', 'r' => 400000000], // Single
                ['t' => 'Cuerpecito', 'a' => null, 'y' => 2023, 'p' => 'Haze', 'g' => 'Reggaeton', 'r' => 200000000], // Single
                ['t' => 'En La De Ella', 'a' => 'Timelezz', 'y' => 2021, 'p' => 'Tainy', 'g' => 'Reggaeton', 'r' => 150000000],
                ['t' => 'Passoa', 'a' => 'Le Clique: Vida Rockstar (X)', 'y' => 2024, 'p' => 'Kapri', 'g' => 'Reggaeton', 'r' => 100000000],
                ['t' => 'Grecia', 'a' => 'Le Clique: Vida Rockstar (X)', 'y' => 2024, 'p' => 'Smash David', 'g' => 'Reggaeton', 'r' => 80000000],
                ['t' => 'Ex-Special', 'a' => null, 'y' => 2023, 'p' => 'Smash David', 'g' => 'Reggaeton', 'r' => 120000000], // Single
                ['t' => 'Memorias', 'a' => null, 'y' => 2022, 'p' => 'Mora', 'g' => 'Reggaeton', 'r' => 500000000], // Single/Collab
            ],
            'Quevedo' => [
                ['t' => 'Quevedo: Bzrp Music Sessions, Vol. 52', 'a' => null, 'y' => 2022, 'p' => 'Bizarrap', 'g' => 'Pop Dance', 'r' => 1600000000], // Single
                ['t' => 'Columbia', 'a' => null, 'y' => 2023, 'p' => 'Bluefire', 'g' => 'Reggaeton', 'r' => 600000000], // Single
                ['t' => 'Playa Del Inglés', 'a' => 'Donde quiero estar', 'y' => 2022, 'p' => 'Ovy On The Drums', 'g' => 'Reggaeton', 'r' => 400000000],
                ['t' => 'Punto G', 'a' => 'Donde quiero estar', 'y' => 2022, 'p' => 'Bluefire', 'g' => 'Reggaeton', 'r' => 350000000],
                ['t' => 'Vista Al Mar', 'a' => 'Donde quiero estar', 'y' => 2022, 'p' => 'Bluefire', 'g' => 'Reggaeton', 'r' => 300000000],
                ['t' => 'Wanda', 'a' => 'Donde quiero estar', 'y' => 2023, 'p' => 'Ovy On The Drums', 'g' => 'Reggaeton', 'r' => 200000000],
                ['t' => 'Ahora Qué', 'a' => 'Donde quiero estar', 'y' => 2023, 'p' => 'Linton', 'g' => 'Trap', 'r' => 180000000],
                ['t' => 'Sin Señal', 'a' => null, 'y' => 2022, 'p' => 'Ovy On The Drums', 'g' => 'Pop Urbano', 'r' => 300000000], // Single
                ['t' => 'El Tonto', 'a' => null, 'y' => 2023, 'p' => 'Lola Indigo', 'g' => 'Pop', 'r' => 250000000], // Single
                ['t' => 'Buenas', 'a' => null, 'y' => 2023, 'p' => 'Saiko', 'g' => 'Reggaeton', 'r' => 150000000], // Single
            ],
            'Bizarrap' => [
                // Bizarrap NO tiene álbumes en el seeder anterior, todo es null
                ['t' => 'Shakira: Bzrp Music Sessions, Vol. 53', 'a' => null, 'y' => 2023, 'p' => 'Bizarrap', 'g' => 'Electropop', 'r' => 900000000],
                ['t' => 'Quevedo: Bzrp Music Sessions, Vol. 52', 'a' => null, 'y' => 2022, 'p' => 'Bizarrap', 'g' => 'Pop Dance', 'r' => 1600000000],
                ['t' => 'Peso Pluma: Bzrp Music Sessions, Vol. 55', 'a' => null, 'y' => 2023, 'p' => 'Bizarrap', 'g' => 'Corridos Tumbados', 'r' => 400000000],
                ['t' => 'Villano Antillano: Bzrp Music Sessions, Vol. 51', 'a' => null, 'y' => 2022, 'p' => 'Bizarrap', 'g' => 'House', 'r' => 300000000],
                ['t' => 'Young Miko: Bzrp Music Sessions, Vol. 58', 'a' => null, 'y' => 2024, 'p' => 'Bizarrap', 'g' => 'Hip Hop', 'r' => 200000000],
                ['t' => 'Milo J: Bzrp Music Sessions, Vol. 57', 'a' => null, 'y' => 2023, 'p' => 'Bizarrap', 'g' => 'Trap', 'r' => 150000000],
                ['t' => 'Rauw Alejandro: Bzrp Music Sessions, Vol. 56', 'a' => null, 'y' => 2023, 'p' => 'Bizarrap', 'g' => 'Electropop', 'r' => 180000000],
                ['t' => 'Arcángel: Bzrp Music Sessions, Vol. 54', 'a' => null, 'y' => 2023, 'p' => 'Bizarrap', 'g' => 'Trap', 'r' => 120000000],
                ['t' => 'Duki: Bzrp Music Sessions, Vol. 50', 'a' => null, 'y' => 2022, 'p' => 'Bizarrap', 'g' => 'Trap', 'r' => 250000000],
                ['t' => 'Nathy Peluso: Bzrp Music Sessions, Vol. 36', 'a' => null, 'y' => 2020, 'p' => 'Bizarrap', 'g' => 'Hip Hop', 'r' => 450000000],
            ],
            'Duki' => [
                ['t' => 'Givenchy', 'a' => null, 'y' => 2022, 'p' => 'Foreign Teck', 'g' => 'Trap', 'r' => 300000000], // Single
                ['t' => 'She Don\'t Give a FO', 'a' => null, 'y' => 2017, 'p' => 'Khea', 'g' => 'Trap', 'r' => 600000000], // Single
                ['t' => 'Goteo', 'a' => null, 'y' => 2019, 'p' => 'Asan', 'g' => 'Trap', 'r' => 500000000], // Single
                ['t' => 'Los del Espacio', 'a' => null, 'y' => 2023, 'p' => 'Big One', 'g' => 'Reggaeton', 'r' => 400000000], // Single
                ['t' => 'Antes de Perderte', 'a' => 'Antes de Ameri', 'y' => 2023, 'p' => 'Big One', 'g' => 'Reggaeton', 'r' => 150000000],
                ['t' => 'Rockstar', 'a' => 'Antes de Ameri', 'y' => 2023, 'p' => 'Asan', 'g' => 'Trap', 'r' => 100000000],
                ['t' => 'Harakiri', 'a' => 'Antes de Ameri', 'y' => 2023, 'p' => 'C.R.O', 'g' => 'Trap', 'r' => 120000000],
                ['t' => 'Barro', 'a' => 'Ameri', 'y' => 2024, 'p' => 'Yesan', 'g' => 'Trap', 'r' => 50000000],
                ['t' => 'Nueva Era', 'a' => 'Ameri', 'y' => 2024, 'p' => 'Asan', 'g' => 'Trap', 'r' => 40000000],
                ['t' => 'Malbec', 'a' => null, 'y' => 2021, 'p' => 'Bizarrap', 'g' => 'Trap', 'r' => 350000000],
            ],
            // Agrego unos pocos más como ejemplo, para no hacer el código infinito,
            // la lógica es replicable para el resto.
            'Anuel AA' => [
                ['t' => 'Ella Quiere Beber', 'a' => 'Real Hasta la Muerte', 'y' => 2018, 'p' => 'Chris Jedi', 'g' => 'Reggaeton', 'r' => 900000000],
                ['t' => 'Quiere Beber', 'a' => 'Real Hasta la Muerte', 'y' => 2018, 'p' => 'Chris Jedi', 'g' => 'Reggaeton', 'r' => 800000000],
                ['t' => 'China', 'a' => 'Emmanuel', 'y' => 2020, 'p' => 'Tainy', 'g' => 'Reggaeton', 'r' => 2000000000],
                ['t' => 'Hasta Que Dios Diga', 'a' => 'Emmanuel', 'y' => 2020, 'p' => 'Bad Bunny', 'g' => 'Reggaeton', 'r' => 600000000],
                ['t' => 'Fútbol y Rumba', 'a' => 'Emmanuel', 'y' => 2020, 'p' => 'Ovy On The Drums', 'g' => 'Reggaeton', 'r' => 500000000],
                ['t' => 'Amanece', 'a' => null, 'y' => 2018, 'p' => 'Haze', 'g' => 'Reggaeton', 'r' => 800000000],
                ['t' => 'Secreto', 'a' => null, 'y' => 2019, 'p' => 'Karol G', 'g' => 'Pop Urbano', 'r' => 1300000000],
                ['t' => 'Adicto', 'a' => null, 'y' => 2019, 'p' => 'Tainy', 'g' => 'Pop Urbano', 'r' => 900000000],
                ['t' => 'Más Rica Que Ayer', 'a' => null, 'y' => 2023, 'p' => 'Mambo Kingz', 'g' => 'Reggaeton', 'r' => 400000000],
                ['t' => 'Sola (Remix)', 'a' => null, 'y' => 2016, 'p' => 'Daddy Yankee', 'g' => 'Trap', 'r' => 700000000],
            ],
            'Myke Towers' => [
                ['t' => 'LALA', 'a' => 'LA VIDA ES UNA', 'y' => 2023, 'p' => 'Jean', 'g' => 'Reggaeton', 'r' => 950000000],
                ['t' => 'La Falda', 'a' => 'LA VIDA ES UNA', 'y' => 2023, 'p' => 'Sky', 'g' => 'Reggaeton', 'r' => 300000000],
                ['t' => 'Diosa', 'a' => null, 'y' => 2020, 'p' => 'Nely', 'g' => 'Reggaeton', 'r' => 600000000],
                ['t' => 'Bandido', 'a' => null, 'y' => 2020, 'p' => 'Juhn', 'g' => 'Pop Urbano', 'r' => 800000000],
                ['t' => 'Girl', 'a' => null, 'y' => 2020, 'p' => 'Nely', 'g' => 'R&B', 'r' => 550000000],
                ['t' => 'Pareja del Año', 'a' => null, 'y' => 2021, 'p' => 'Sebastian Yatra', 'g' => 'Pop Urbano', 'r' => 900000000],
                ['t' => 'Piensan', 'a' => null, 'y' => 2019, 'p' => 'Nely', 'g' => 'Trap', 'r' => 300000000],
                ['t' => 'La Curiosidad', 'a' => null, 'y' => 2020, 'p' => 'Jay Wheeler', 'g' => 'Reggaeton', 'r' => 1100000000],
                ['t' => 'La Pantera Negra', 'a' => 'La Pantera Negra', 'y' => 2024, 'p' => 'Full Harmony', 'g' => 'Trap', 'r' => 50000000],
                ['t' => 'Otra Noche', 'a' => 'La Pantera Negra', 'y' => 2024, 'p' => 'Darell', 'g' => 'Reggaeton', 'r' => 80000000],
            ],
            'Mora' => [
                ['t' => 'La Inocente', 'a' => 'Microdosis', 'y' => 2022, 'p' => 'Mora', 'g' => 'Reggaeton', 'r' => 600000000],
                ['t' => 'Memorias', 'a' => 'Microdosis', 'y' => 2022, 'p' => 'Mora', 'g' => 'Reggaeton', 'r' => 500000000],
                ['t' => 'Tus Lágrimas', 'a' => 'Microdosis', 'y' => 2022, 'p' => 'Mora', 'g' => 'Synth Pop', 'r' => 200000000],
                ['t' => 'Reina', 'a' => 'Estrella', 'y' => 2023, 'p' => 'Saiko', 'g' => 'Reggaeton', 'r' => 300000000],
                ['t' => 'Donde Se Aprende A Querer?', 'a' => 'Estrella', 'y' => 2023, 'p' => 'Mora', 'g' => 'Pop Urbano', 'r' => 150000000],
                ['t' => 'Volando (Remix)', 'a' => null, 'y' => 2021, 'p' => 'Mora', 'g' => 'Reggaeton', 'r' => 700000000],
                ['t' => '512', 'a' => null, 'y' => 2021, 'p' => 'Jhayco', 'g' => 'Reggaeton', 'r' => 450000000],
                ['t' => 'Hibiki', 'a' => null, 'y' => 2024, 'p' => 'Bad Bunny', 'g' => 'House', 'r' => 300000000],
                ['t' => 'Modelito', 'a' => null, 'y' => 2023, 'p' => 'Yovngchimi', 'g' => 'Trap', 'r' => 200000000],
                ['t' => 'Pasajero', 'a' => 'Estrella', 'y' => 2023, 'p' => 'Mora', 'g' => 'Electronic', 'r' => 100000000],
            ],
            'Tokischa' => [
                ['t' => 'Linda', 'a' => null, 'y' => 2021, 'p' => 'Rosalía', 'g' => 'Dembow', 'r' => 300000000],
                ['t' => 'Delincuente', 'a' => null, 'y' => 2022, 'p' => 'Anuel AA', 'g' => 'Dembow', 'r' => 150000000],
                ['t' => 'Estilazo', 'a' => null, 'y' => 2022, 'p' => 'Marshmello', 'g' => 'EDM', 'r' => 120000000],
                ['t' => 'Perra', 'a' => null, 'y' => 2021, 'p' => 'J Balvin', 'g' => 'Dembow', 'r' => 80000000],
                ['t' => 'Sistema de Patio', 'a' => null, 'y' => 2022, 'p' => 'Treintisiete', 'g' => 'Dembow', 'r' => 50000000],
                ['t' => 'Desacato Escolar', 'a' => null, 'y' => 2020, 'p' => 'Yomel', 'g' => 'Dembow', 'r' => 90000000],
                ['t' => 'Singamo', 'a' => null, 'y' => 2021, 'p' => 'Yomel', 'g' => 'Dembow', 'r' => 70000000],
                ['t' => 'Chulo pt.2', 'a' => null, 'y' => 2023, 'p' => 'Bad Gyal', 'g' => 'Dembow', 'r' => 350000000],
                ['t' => 'Kilos de Amor', 'a' => null, 'y' => 2023, 'p' => 'Natanael Cano', 'g' => 'Corridos', 'r' => 60000000],
                ['t' => 'Sol', 'a' => null, 'y' => 2024, 'p' => 'Tokischa', 'g' => 'Pop', 'r' => 20000000],
            ],
            'Nicki Nicole' => [
                ['t' => 'Dispara ***', 'a' => 'Alma', 'y' => 2023, 'p' => 'Tatool', 'g' => 'Hip Hop', 'r' => 250000000],
                ['t' => '8 AM', 'a' => 'Alma', 'y' => 2023, 'p' => 'Tatool', 'g' => 'Reggaeton', 'r' => 120000000],
                ['t' => 'Wapo Traketero', 'a' => null, 'y' => 2019, 'p' => 'Gonzalo Ferreyra', 'g' => 'R&B', 'r' => 200000000],
                ['t' => 'Colocao', 'a' => null, 'y' => 2020, 'p' => 'Bizarrap', 'g' => 'Trap', 'r' => 180000000],
                ['t' => 'Mamichula', 'a' => null, 'y' => 2020, 'p' => 'Bizarrap', 'g' => 'R&B', 'r' => 500000000],
                ['t' => 'No Voy A Llorar :(', 'a' => 'Alma', 'y' => 2023, 'p' => 'Tatool', 'g' => 'Pop Urbano', 'r' => 80000000],
                ['t' => 'Marisola (Remix)', 'a' => null, 'y' => 2022, 'p' => 'Cris MJ', 'g' => 'Reggaeton', 'r' => 350000000],
                ['t' => 'Entre Nosotros (Remix)', 'a' => null, 'y' => 2022, 'p' => 'Big One', 'g' => 'Trap Pop', 'r' => 450000000],
                ['t' => 'Formentera', 'a' => null, 'y' => 2021, 'p' => 'Aitana', 'g' => 'Pop', 'r' => 150000000],
                ['t' => 'Ojos Verdes', 'a' => null, 'y' => 2024, 'p' => 'Tatool', 'g' => 'Cumbia', 'r' => 60000000],
            ],
            'Eladio Carrión' => [
                ['t' => 'Coco Chanel', 'a' => '3MEN2 KBRN', 'y' => 2023, 'p' => 'DVLP', 'g' => 'Trap', 'r' => 550000000],
                ['t' => 'Si La Calle Llama', 'a' => '3MEN2 KBRN', 'y' => 2023, 'p' => 'Hydro', 'g' => 'Trap', 'r' => 200000000],
                ['t' => 'Mbappe', 'a' => null, 'y' => 2022, 'p' => 'Hydro', 'g' => 'Trap', 'r' => 300000000],
                ['t' => 'Kemba Walker', 'a' => null, 'y' => 2020, 'p' => 'Bad Bunny', 'g' => 'Trap', 'r' => 250000000],
                ['t' => 'Tanta Droga', 'a' => 'Sol María', 'y' => 2024, 'p' => 'DVLP', 'g' => 'Trap', 'r' => 40000000],
                ['t' => 'Hey Lil Mama', 'a' => 'Sol María', 'y' => 2024, 'p' => 'Mambo Kingz', 'g' => 'Reggaeton', 'r' => 60000000],
                ['t' => 'No Te Deseo el Mal', 'a' => null, 'y' => 2022, 'p' => 'Karol G', 'g' => 'Reggaeton', 'r' => 180000000],
                ['t' => 'Paz Mental', 'a' => null, 'y' => 2021, 'p' => 'Andre The Giant', 'g' => 'Trap', 'r' => 100000000],
                ['t' => 'Bzrp Music Sessions, Vol. 40', 'a' => null, 'y' => 2021, 'p' => 'Bizarrap', 'g' => 'Trap', 'r' => 220000000],
                ['t' => 'Sigue Bailándome', 'a' => null, 'y' => 2019, 'p' => 'Yandel', 'g' => 'Reggaeton', 'r' => 150000000],
            ],
            'Yandel' => [
                ['t' => 'Yandel 150', 'a' => 'Resistencia', 'y' => 2023, 'p' => 'Jhayco', 'g' => 'Reggaeton', 'r' => 850000000],
                ['t' => 'Borracho y Loco', 'a' => 'Elyte', 'y' => 2024, 'p' => 'Myke Towers', 'g' => 'Reggaeton', 'r' => 50000000],
                ['t' => 'Hasta Abajo Le Doy', 'a' => 'Resistencia', 'y' => 2023, 'p' => 'Franco El Gorila', 'g' => 'Reggaeton', 'r' => 120000000],
                ['t' => 'Encantadora', 'a' => null, 'y' => 2015, 'p' => 'Haze', 'g' => 'Reggaeton', 'r' => 600000000],
                ['t' => 'Explícale', 'a' => null, 'y' => 2017, 'p' => 'Bad Bunny', 'g' => 'Trap', 'r' => 350000000],
                ['t' => 'Moviendo Caderas', 'a' => null, 'y' => 2014, 'p' => 'Daddy Yankee', 'g' => 'Reggaeton', 'r' => 450000000],
                ['t' => 'Calentura', 'a' => null, 'y' => 2015, 'p' => 'Haze', 'g' => 'Reggaeton', 'r' => 200000000],
                ['t' => 'Sigo Enamorado de Ti', 'a' => 'Elyte', 'y' => 2024, 'p' => 'Tainy', 'g' => 'Reggaeton', 'r' => 30000000],
                ['t' => 'Cuidao', 'a' => null, 'y' => 2019, 'p' => 'Messiah', 'g' => 'Reggaeton', 'r' => 80000000],
                ['t' => 'Plakito', 'a' => null, 'y' => 2014, 'p' => 'Gadiel', 'g' => 'Reggaeton', 'r' => 100000000],
            ],
            'Nicky Jam' => [
                ['t' => 'X (EQUIS)', 'a' => null, 'y' => 2018, 'p' => 'Afro Bros', 'g' => 'Urban Dance', 'r' => 1100000000],
                ['t' => 'El Perdón', 'a' => 'Fénix', 'y' => 2015, 'p' => 'Saga WhiteBlack', 'g' => 'Reggaeton', 'r' => 1400000000],
                ['t' => 'Hasta el Amanecer', 'a' => 'Fénix', 'y' => 2016, 'p' => 'Saga WhiteBlack', 'g' => 'Reggaeton', 'r' => 1200000000],
                ['t' => 'Travesuras', 'a' => null, 'y' => 2014, 'p' => 'Saga WhiteBlack', 'g' => 'Reggaeton', 'r' => 900000000],
                ['t' => 'El Amante', 'a' => 'Fénix', 'y' => 2017, 'p' => 'Saga WhiteBlack', 'g' => 'Reggaeton', 'r' => 1000000000],
                ['t' => 'Whine Up', 'a' => 'Íntimo', 'y' => 2019, 'p' => 'Anuel AA', 'g' => 'Reggaeton', 'r' => 300000000],
                ['t' => '69', 'a' => null, 'y' => 2023, 'p' => 'Feid', 'g' => 'Reggaeton', 'r' => 150000000],
                ['t' => 'Cangrinaje', 'a' => 'Insomnio', 'y' => 2024, 'p' => 'Trueno', 'g' => 'Reggaeton', 'r' => 40000000],
                ['t' => 'Polvo', 'a' => 'Infinity', 'y' => 2020, 'p' => 'Myke Towers', 'g' => 'Reggaeton', 'r' => 250000000],
                ['t' => 'Ojos Rojos', 'a' => null, 'y' => 2022, 'p' => 'The Rude Boyz', 'g' => 'Reggaeton', 'r' => 180000000],
            ],
            'Ozuna' => [
                ['t' => 'Taki Taki', 'a' => null, 'y' => 2018, 'p' => 'DJ Snake', 'g' => 'Global Pop', 'r' => 1500000000],
                ['t' => 'Criminal', 'a' => null, 'y' => 2017, 'p' => 'Natti Natasha', 'g' => 'Reggaeton', 'r' => 1200000000],
                ['t' => 'Se Preparó', 'a' => 'Odisea', 'y' => 2017, 'p' => 'Chris Jedi', 'g' => 'Reggaeton', 'r' => 1100000000],
                ['t' => 'Dile Que Tu Me Quieres', 'a' => 'Odisea', 'y' => 2017, 'p' => 'Super Yei', 'g' => 'Reggaeton', 'r' => 800000000],
                ['t' => 'Caramelo', 'a' => 'ENOC', 'y' => 2020, 'p' => 'Tainy', 'g' => 'Reggaeton', 'r' => 700000000],
                ['t' => 'Baila Baila Baila', 'a' => 'Nibiru', 'y' => 2019, 'p' => 'Mambo Kingz', 'g' => 'Reggaeton', 'r' => 600000000],
                ['t' => 'Vaina Loca', 'a' => 'Aura', 'y' => 2018, 'p' => 'Manuel Turizo', 'g' => 'Pop Urbano', 'r' => 1000000000],
                ['t' => 'Monotonía', 'a' => null, 'y' => 2022, 'p' => 'Shakira', 'g' => 'Bachata', 'r' => 500000000],
                ['t' => 'Hey Mor', 'a' => null, 'y' => 2022, 'p' => 'Feid', 'g' => 'Reggaeton', 'r' => 900000000],
                ['t' => 'Te Vas', 'a' => 'Odisea', 'y' => 2016, 'p' => 'Chris Jedi', 'g' => 'Reggaeton', 'r' => 750000000],
            ],
            'El Alfa' => [
                ['t' => 'La Mamá de la Mamá', 'a' => null, 'y' => 2021, 'p' => 'Chael', 'g' => 'Dembow', 'r' => 450000000],
                ['t' => 'Gogo Dance', 'a' => 'Sabiduría', 'y' => 2022, 'p' => 'Chael', 'g' => 'Dembow', 'r' => 350000000],
                ['t' => '4K', 'a' => 'El Androide', 'y' => 2020, 'p' => 'Chael', 'g' => 'Dembow', 'r' => 400000000],
                ['t' => 'Coronao Now', 'a' => 'El Androide', 'y' => 2019, 'p' => 'Lil Pump', 'g' => 'Dembow', 'r' => 300000000],
                ['t' => 'Suave', 'a' => 'El Hombre', 'y' => 2018, 'p' => 'Chael', 'g' => 'Dembow', 'r' => 250000000],
                ['t' => 'La Romana', 'a' => null, 'y' => 2018, 'p' => 'Bad Bunny', 'g' => 'Trap/Dembow', 'r' => 600000000],
                ['t' => 'Fulanito', 'a' => null, 'y' => 2021, 'p' => 'Becky G', 'g' => 'Merengue', 'r' => 380000000],
                ['t' => 'Plebe', 'a' => null, 'y' => 2023, 'p' => 'Peso Pluma', 'g' => 'Reggaeton', 'r' => 150000000],
                ['t' => 'Los Aparatos', 'a' => 'Sagitario', 'y' => 2022, 'p' => 'Trueno', 'g' => 'Dembow', 'r' => 180000000],
                ['t' => 'Dora', 'a' => null, 'y' => 2024, 'p' => 'Chael', 'g' => 'Dembow', 'r' => 40000000],
            ],
            'Nathy Peluso' => [
                ['t' => 'Bzrp Music Sessions, Vol. 36', 'a' => null, 'y' => 2020, 'p' => 'Bizarrap', 'g' => 'Hip Hop', 'r' => 450000000],
                ['t' => 'Mafiosa', 'a' => null, 'y' => 2021, 'p' => 'Rafa Arcaute', 'g' => 'Salsa', 'r' => 120000000],
                ['t' => 'Aprender a Amar', 'a' => 'Grasa', 'y' => 2024, 'p' => 'Manuel Lara', 'g' => 'Hip Hop', 'r' => 30000000],
                ['t' => 'Buenos Aires', 'a' => 'Calambre', 'y' => 2020, 'p' => 'Rafa Arcaute', 'g' => 'Neo-Soul', 'r' => 90000000],
                ['t' => 'Sana Sana', 'a' => 'Calambre', 'y' => 2020, 'p' => 'Illmind', 'g' => 'Hip Hop', 'r' => 85000000],
                ['t' => 'Delito', 'a' => 'Calambre', 'y' => 2020, 'p' => 'Rafa Arcaute', 'g' => 'Pop', 'r' => 110000000],
                ['t' => 'Vivir Así Es Morir De Amor', 'a' => null, 'y' => 2021, 'p' => 'Rafa Arcaute', 'g' => 'Pop Rock', 'r' => 140000000],
                ['t' => 'Ateo', 'a' => null, 'y' => 2021, 'p' => 'C. Tangana', 'g' => 'Bachata', 'r' => 380000000],
                ['t' => 'Real', 'a' => 'Grasa', 'y' => 2024, 'p' => 'Didi Gutman', 'g' => 'Pop', 'r' => 150000000],
                ['t' => 'Envidia', 'a' => 'Grasa', 'y' => 2024, 'p' => 'Manuel Lara', 'g' => 'Pop Urbano', 'r' => 20000000],
            ],

            ];

        $songsToInsert = [];

        foreach ($catalog as $artistName => $songs) {
            // Verificar si el artista existe en la BD
            if (!isset($artists[$artistName])) {
                continue;
            }

            $artistId = $artists[$artistName];
            $artistCountry = $artistCountries[$artistName] ?? 'Desconocido';

            foreach ($songs as $s) {
                // Verificar ID album. Si es null o no existe en la BD, queda null.
                $albumId = ($s['a'] && isset($albums[$s['a']])) ? $albums[$s['a']] : null;
                
                // Generar fecha random dentro del año especificado
                $randomDate = Carbon::create($s['y'], rand(1, 12), rand(1, 28))->format('Y-m-d');

                $songsToInsert[] = [
                    'titulo' => $s['t'],
                    'id_artista' => $artistId,
                    'id_album' => $albumId,
                    'productor' => $s['p'],
                    'registration_date' => $randomDate,
                    'pais' => $artistCountry, // Usamos país del artista por defecto
                    'anio' => $s['y'],
                    'genero' => $s['g'],
                    'reproducciones' => $s['r'],
                ];
            }
        }

        // Insertamos en lotes para mayor velocidad
        foreach (array_chunk($songsToInsert, 100) as $chunk) {
            Song::insert($chunk);
        }
    }
}