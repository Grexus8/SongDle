<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;

class ArtistSeeder extends Seeder
{
    public function run(): void
    {
        // Primero limpiamos la tabla para evitar duplicados al volver a correr el seeder
        // Artist::truncate(); // Descomenta esto si quieres borrar los datos anteriores

        $artists = [
            [
                'nombre' => 'Bad Bunny',
                'pais' => 'Puerto Rico',
                'genero' => 'hombre',
                'debut' => 2016,
                'cantidad_albumes' => 7, // X100PRE, Oasis, YHLQMDLG, LQNIAS, El Último Tour, Un Verano Sin Ti, Nadie Sabe...
                'premios' => 'Grammy, Latin Grammy, Billboard, Apple Music Awards',
                'oyentes_mensuales' => 83500000,
            ],
            [
                'nombre' => 'Jhayco',
                'pais' => 'Puerto Rico',
                'genero' => 'hombre',
                'debut' => 2017,
                'cantidad_albumes' => 3, // Famouz, Timelezz, Le Clique
                'premios' => 'Latin Grammy, Premios Juventud',
                'oyentes_mensuales' => 32000000,
            ],
            [
                'nombre' => 'Feid',
                'pais' => 'Colombia',
                'genero' => 'hombre',
                'debut' => 2015,
                'cantidad_albumes' => 6, // Así Como Suena, 19, Ferxxo Vol 1, Inter Shibuya, Feliz Cumpleaños..., Mor No Le Temas
                'premios' => 'Latin Grammy, Premios Tu Música Urbana',
                'oyentes_mensuales' => 44000000,
            ],
            [
                'nombre' => 'Quevedo',
                'pais' => 'España',
                'genero' => 'hombre',
                'debut' => 2020,
                'cantidad_albumes' => 1, // Donde quiero estar
                'premios' => 'Latin Grammy, LOS40 Music Awards',
                'oyentes_mensuales' => 30000000,
            ],
            [
                'nombre' => 'Anuel AA',
                'pais' => 'Puerto Rico',
                'genero' => 'hombre',
                'debut' => 2016, // Explosion mainstream con Real Hasta la Muerte
                'cantidad_albumes' => 5, // RHLM, Emmanuel, Las Leyendas 1, LLNM 2, Rompecorazones (EP/Album)
                'premios' => 'Billboard Latin Music Awards, Latin AMA',
                'oyentes_mensuales' => 34000000,
            ],
            [
                'nombre' => 'Myke Towers',
                'pais' => 'Puerto Rico',
                'genero' => 'hombre',
                'debut' => 2016,
                'cantidad_albumes' => 5, // Easy Money Baby, Lyke Mike, La Vida Es Una, LVEU: Vive La Tuya, La Pantera Negra
                'premios' => 'Billboard Latin, Premios Tu Música Urbana',
                'oyentes_mensuales' => 38000000,
            ],
            [
                'nombre' => 'Rauw Alejandro',
                'pais' => 'Puerto Rico',
                'genero' => 'hombre',
                'debut' => 2016,
                'cantidad_albumes' => 5, // Afrodisíaco, Vice Versa, Saturno, Playa Saturno, Cosa Nuestra
                'premios' => 'Latin Grammy, Billboard',
                'oyentes_mensuales' => 51000000,
            ],
            [
                'nombre' => 'Karol G',
                'pais' => 'Colombia',
                'genero' => 'mujer',
                'debut' => 2013,
                'cantidad_albumes' => 5, // Unstoppable, Ocean, KG0516, Mañana Será Bonito, Mañana Será Bonito (Bichota Season)
                'premios' => 'Grammy, Latin Grammy, Billboard Women in Music',
                'oyentes_mensuales' => 58000000,
            ],
            [
                'nombre' => 'Duki',
                'pais' => 'Argentina',
                'genero' => 'hombre',
                'debut' => 2016,
                'cantidad_albumes' => 4, // Súper Sangre Joven, Desde el Fin del Mundo, Antes de Ameri, Ameri
                'premios' => 'Premios Gardel',
                'oyentes_mensuales' => 23000000,
            ],
            [
                'nombre' => 'Nicki Nicole',
                'pais' => 'Argentina',
                'genero' => 'mujer',
                'debut' => 2019,
                'cantidad_albumes' => 3, // Recuerdos, Parte de Mi, Alma
                'premios' => 'Premios Gardel, Los40',
                'oyentes_mensuales' => 22000000,
            ],
            [
                'nombre' => 'Bizarrap',
                'pais' => 'Argentina',
                'genero' => 'hombre',
                'debut' => 2017,
                'cantidad_albumes' => 0, // No tiene álbumes de estudio, solo Music Sessions (Singles)
                'premios' => 'Latin Grammy, Lo Nuestro',
                'oyentes_mensuales' => 41000000,
            ],
            [
                'nombre' => 'Eladio Carrión',
                'pais' => 'Puerto Rico',
                'genero' => 'hombre',
                'debut' => 2017,
                'cantidad_albumes' => 5, // Sauce Boyz, Monarca, Sauce Boyz 2, 3MEN2 KBRN, Sol María
                'premios' => 'Latin Grammy',
                'oyentes_mensuales' => 24000000,
            ],
            [
                'nombre' => 'Yandel',
                'pais' => 'Puerto Rico',
                'genero' => 'hombre',
                'debut' => 1995, // Comienzo de carrera (antes de solista oficial en 2003)
                'cantidad_albumes' => 7, // Quien Contra Mí, De Líder a Leyenda, Dangerous, Update, The One, Resistencia, Elyte
                'premios' => 'Latin Grammy, Billboard',
                'oyentes_mensuales' => 20000000,
            ],
            [
                'nombre' => 'Mora',
                'pais' => 'Puerto Rico',
                'genero' => 'hombre',
                'debut' => 2018,
                'cantidad_albumes' => 4, // Primer Día de Clases, Microdosis, Paraíso, Estrella
                'premios' => 'Premios Juventud',
                'oyentes_mensuales' => 26000000,
            ],
            [
                'nombre' => 'Nicky Jam',
                'pais' => 'Estados Unidos', // Nació en USA, aunque se crió en PR y triunfó en Colombia
                'genero' => 'hombre',
                'debut' => 1994,
                'cantidad_albumes' => 7, // Distinto a los demás, The Black Carpet, Fénix, Íntimo, Infinity, Insomnio...
                'premios' => 'Billboard Latin Music Awards, Latin Grammy',
                'oyentes_mensuales' => 28000000,
            ],
            [
                'nombre' => 'Ozuna',
                'pais' => 'Puerto Rico',
                'genero' => 'hombre',
                'debut' => 2012,
                'cantidad_albumes' => 6, // Odisea, Aura, Nibiru, ENOC, Ozutochi, Cosmo
                'premios' => 'Billboard Latin Music Awards, Guinness Records',
                'oyentes_mensuales' => 42000000,
            ],
            [
                'nombre' => 'Tokischa',
                'pais' => 'República Dominicana',
                'genero' => 'mujer',
                'debut' => 2018,
                'cantidad_albumes' => 0, // Principalmente singles y colaboraciones
                'premios' => 'Premios Heat',
                'oyentes_mensuales' => 13000000,
            ],
            [
                'nombre' => 'El Alfa',
                'pais' => 'República Dominicana',
                'genero' => 'hombre',
                'debut' => 2008,
                'cantidad_albumes' => 5, // El Hombre, El Androide, Sabiduría, Sagitario, El Rey del Dembow
                'premios' => 'Premios Soberano, Lo Nuestro',
                'oyentes_mensuales' => 19000000,
            ],
            [
                'nombre' => 'Nathy Peluso',
                'pais' => 'Argentina',
                'genero' => 'mujer',
                'debut' => 2017,
                'cantidad_albumes' => 2, // Calambre, Grasa
                'premios' => 'Latin Grammy',
                'oyentes_mensuales' => 7000000,
            ],
            
        ];

        // Insertar por lotes (batch) es mejor que pasar todo el array gigante de una vez si crece mucho
        foreach (array_chunk($artists, 50) as $chunk) {
            Artist::insert($chunk);
        }
    }
}