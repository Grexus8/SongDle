<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SpotifySeeder extends Seeder
{
    private int $requestCount = 0;
    private int $pauseMs      = 1100; // MusicBrainz: máx 1 req/s

    // ── Artistas a importar ───────────────────────────────────────
    private array $artistNames = [
        'Bad Bunny', 'Daddy Yankee', 'J Balvin', 'Karol G', 'Ozuna',
        'Chencho Corleone', 'Rauw Alejandro', 'Feid', 'Myke Towers', 'Eladio Carrion',
        'Jhayco', 'Mora', 'Arcangel', 'Bryant Myers', 'Maluma',
        'Quevedo', 'Morad', 'Saiko', 'Rels B', 'Yung Beef',
        'Cruz Cafuné', 'Dellafuente', 'Kaydy Cain', 'Natos y Waor', 'Recycled J',
        'Ptazeta', 'Omar Montes', 'Bad Gyal', 'Lola Indigo',
        'Duki', 'Bizarrap', 'Trueno', 'Nicki Nicole', 'KHEA',
        'LIT killah', 'YSY A', 'Tiago PZK', 'WOS', 'Milo J',
        'Paulo Londra', 'Cazzu', 'Maria Becerra',
        'Rosalía', 'C. Tangana', 'Young Miko',
        'Anuel AA', 'Don Omar', 'Nicky Jam', 'Sech', 'Farruko',
        'Justin Quiles', 'Dalex', 'Lenny Tavarez', 'Nio Garcia', 'El Alfa',
        'Wisin', 'Yandel', 'Zion y Lennox',
    ];

    // ── Oyentes mensuales aproximados (Spotify, mayo 2025) ────────
    private array $monthlyListeners = [
        'Bad Bunny'        => 44_000_000, 'Daddy Yankee'     => 28_000_000,
        'J Balvin'         => 31_000_000, 'Karol G'          => 39_000_000,
        'Ozuna'            => 30_000_000, 'Chencho Corleone' =>  8_000_000,
        'Rauw Alejandro'   => 27_000_000, 'Feid'             => 22_000_000,
        'Myke Towers'      => 18_000_000, 'Eladio Carrion'   => 10_000_000,
        'Jhayco'           => 12_000_000, 'Mora'             =>  6_000_000,
        'Arcangel'         => 14_000_000, 'Bryant Myers'     =>  9_000_000,
        'Maluma'           => 29_000_000, 'Quevedo'          => 16_000_000,
        'Morad'            =>  8_000_000, 'Saiko'            =>  5_000_000,
        'Rels B'           =>  4_500_000, 'Yung Beef'        =>  3_000_000,
        'Cruz Cafuné'      =>  2_500_000, 'Dellafuente'      =>  2_000_000,
        'Kaydy Cain'       =>  1_500_000, 'Natos y Waor'     =>  2_800_000,
        'Recycled J'       =>  3_200_000, 'Ptazeta'          =>  1_800_000,
        'Omar Montes'      =>  4_000_000, 'Bad Gyal'         =>  6_000_000,
        'Lola Indigo'      =>  2_500_000, 'Duki'             => 12_000_000,
        'Bizarrap'         => 20_000_000, 'Trueno'           =>  5_000_000,
        'Nicki Nicole'     =>  9_000_000, 'KHEA'             =>  4_000_000,
        'LIT killah'       =>  4_500_000, 'YSY A'            =>  2_000_000,
        'Tiago PZK'        =>  6_000_000, 'WOS'              =>  3_500_000,
        'Milo J'           =>  8_000_000, 'Paulo Londra'     => 11_000_000,
        'Cazzu'            =>  9_000_000, 'Maria Becerra'    => 14_000_000,
        'Rosalía'          => 18_000_000, 'C. Tangana'       =>  7_000_000,
        'Young Miko'       => 11_000_000, 'Anuel AA'         => 22_000_000,
        'Don Omar'         => 20_000_000, 'Nicky Jam'        => 18_000_000,
        'Sech'             => 10_000_000, 'Farruko'          => 15_000_000,
        'Justin Quiles'    =>  7_000_000, 'Dalex'            =>  5_000_000,
        'Lenny Tavarez'    =>  4_000_000, 'Nio Garcia'       =>  4_500_000,
        'El Alfa'          => 12_000_000, 'Wisin'            => 10_000_000,
        'Yandel'           =>  8_000_000, 'Zion y Lennox'    =>  6_000_000,
    ];

    // ── Premios más destacados ────────────────────────────────────
    private array $awards = [
        'Bad Bunny'      => 'Latin Grammy 2020, Grammy 2023, Billboard Music Award 2023',
        'Daddy Yankee'   => 'Billboard Lifetime Achievement 2021, Latin Grammy 2021',
        'J Balvin'       => 'Latin Grammy 2018, Billboard Music Award 2020',
        'Karol G'        => 'Latin Grammy 2023, Billboard Music Award 2024',
        'Ozuna'          => 'Latin Grammy 2018, Billboard Music Award 2019',
        'Rauw Alejandro' => 'Latin Grammy 2022, Billboard Music Award 2022',
        'Feid'           => 'Latin Grammy 2023',
        'Maluma'         => 'Latin Grammy 2019, Billboard Music Award 2019',
        'Rosalía'        => 'Grammy 2023, Latin Grammy 2022, Latin Grammy 2019',
        'C. Tangana'     => 'Premio Ondas 2021, Latin Grammy 2022',
        'Bizarrap'       => 'Latin Grammy 2023, Grammy 2024',
        'Anuel AA'       => 'Billboard Music Award 2020',
        'Don Omar'       => 'Latin Grammy 2006, Premio Lo Nuestro 2008',
        'Nicky Jam'      => 'Latin Grammy 2017, Premio Lo Nuestro 2016',
        'Farruko'        => 'Latin Grammy 2016',
        'Paulo Londra'   => 'Latin Grammy 2019',
        'Maria Becerra'  => 'MTV Miaw 2022, Gardel de Oro 2023',
        'Duki'           => 'Gardel de Oro 2023, MTV Miaw 2023',
        'Young Miko'     => 'Latin Grammy 2024',
        'Myke Towers'    => 'Billboard Music Award 2022',
        'Bad Gyal'       => 'Premio MIN 2019',
        'Nicki Nicole'   => 'Gardel de Oro 2021',
    ];

    // ── Códigos ISO → nombre completo del país ────────────────────
    private array $countryCodes = [
        'AF'=>'Afganistán','AL'=>'Albania','DZ'=>'Argelia','AD'=>'Andorra',
        'AO'=>'Angola','AR'=>'Argentina','AM'=>'Armenia','AU'=>'Australia',
        'AT'=>'Austria','AZ'=>'Azerbaiyán','BS'=>'Bahamas','BD'=>'Bangladesh',
        'BB'=>'Barbados','BY'=>'Bielorrusia','BE'=>'Bélgica','BZ'=>'Belice',
        'BO'=>'Bolivia','BA'=>'Bosnia y Herzegovina','BR'=>'Brasil',
        'BG'=>'Bulgaria','CA'=>'Canadá','CL'=>'Chile','CN'=>'China',
        'CO'=>'Colombia','CR'=>'Costa Rica','HR'=>'Croacia','CU'=>'Cuba',
        'CZ'=>'República Checa','DK'=>'Dinamarca','DO'=>'República Dominicana',
        'EC'=>'Ecuador','EG'=>'Egipto','SV'=>'El Salvador','EE'=>'Estonia',
        'ET'=>'Etiopía','FI'=>'Finlandia','FR'=>'Francia','DE'=>'Alemania',
        'GH'=>'Ghana','GR'=>'Grecia','GT'=>'Guatemala','HT'=>'Haití',
        'HN'=>'Honduras','HU'=>'Hungría','IS'=>'Islandia','IN'=>'India',
        'ID'=>'Indonesia','IR'=>'Irán','IQ'=>'Irak','IE'=>'Irlanda',
        'IL'=>'Israel','IT'=>'Italia','JM'=>'Jamaica','JP'=>'Japón',
        'KZ'=>'Kazajistán','KE'=>'Kenia','KR'=>'Corea del Sur',
        'KW'=>'Kuwait','LV'=>'Letonia','LB'=>'Líbano','LY'=>'Libia',
        'LT'=>'Lituania','LU'=>'Luxemburgo','MY'=>'Malasia','MX'=>'México',
        'MD'=>'Moldavia','MA'=>'Marruecos','NP'=>'Nepal','NL'=>'Países Bajos',
        'NZ'=>'Nueva Zelanda','NI'=>'Nicaragua','NG'=>'Nigeria','NO'=>'Noruega',
        'PK'=>'Pakistán','PA'=>'Panamá','PY'=>'Paraguay','PE'=>'Perú',
        'PH'=>'Filipinas','PL'=>'Polonia','PT'=>'Portugal','PR'=>'Puerto Rico',
        'RO'=>'Rumanía','RU'=>'Rusia','SA'=>'Arabia Saudita','RS'=>'Serbia',
        'SG'=>'Singapur','SK'=>'Eslovaquia','SI'=>'Eslovenia','ZA'=>'Sudáfrica',
        'ES'=>'España','LK'=>'Sri Lanka','SE'=>'Suecia','CH'=>'Suiza',
        'TW'=>'Taiwán','TH'=>'Tailandia','TN'=>'Túnez','TR'=>'Turquía',
        'UA'=>'Ucrania','AE'=>'Emiratos Árabes Unidos','GB'=>'Reino Unido',
        'US'=>'Estados Unidos','UY'=>'Uruguay','VE'=>'Venezuela','VN'=>'Vietnam',
        'XW'=>'Internacional','XE'=>'Europa',
    ];

    // ── Config ────────────────────────────────────────────────────
    private int $minTracksPerAlbum  = 2;   // álbumes con menos canciones se descartan

    // ─────────────────────────────────────────────────────────────

    public function run(): void
    {
        $this->command->info('🎵 Iniciando seeder con MusicBrainz API...');
        $this->command->warn('   ℹ MusicBrainz: 1 req/s. Tardará ~15-20 min para ' . count($this->artistNames) . ' artistas.');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('songs')->truncate();
        DB::table('albums')->truncate();
        DB::table('artists')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('🗑  Tablas limpiadas.');

        $total = count($this->artistNames);
        $bar   = $this->command->getOutput()->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->start();

        foreach ($this->artistNames as $name) {
            $bar->setMessage($name);
            try {
                $this->processArtist($name);
            } catch (\Exception $e) {
                $this->command->newLine();
                $this->command->warn("⚠ Error '{$name}': " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine(2);
        $this->printSummary();
        $this->command->line("   Requests API: {$this->requestCount}");
    }

    // ─────────────────────────────────────────────────────────────
    //  PROCESO POR ARTISTA
    // ─────────────────────────────────────────────────────────────

    private function processArtist(string $name): void
    {
        $mbid = $this->searchArtist($name);
        if (!$mbid) {
            $this->command->newLine();
            $this->command->warn("   ⚠ No encontrado: '{$name}'");
            return;
        }

        $artistData = $this->getArtist($mbid);
        if (!$artistData) return;

        $artistDbId = $this->insertArtist($artistData, $name);
        if (!$artistDbId) return;

        $releases = $this->getArtistReleases($mbid);
        if (empty($releases)) return;

        // Ordenar por fecha: más antiguo primero
        usort($releases, fn($a, $b) => strcmp($a['date'] ?? '9999', $b['date'] ?? '9999'));
        $selected = $releases;

        // Actualizar debut con el álbum más antiguo
        $oldest    = $selected[0] ?? null;
        $debutYear = $this->extractYear($oldest['date'] ?? null);
        if ($debutYear > 1900) {
            DB::table('artists')->where('id_artista', $artistDbId)->update(['debut' => $debutYear]);
        }

        $areaName = $artistData['area']['name'] ?? $artistData['begin-area']['name'] ?? null;
        $country  = $this->resolveCountry($artistData['country'] ?? null, $artistData['tags'] ?? [], $areaName);
        $genre   = $this->extractGenre($artistData['tags'] ?? []);

        $totalSongsInserted = 0;

        foreach ($selected as $release) {
            if (empty($release['id'])) continue;

            $releaseDetail = $this->getRelease($release['id']);
            if (!$releaseDetail) continue;

            // Contar canciones del álbum antes de insertarlo
            $trackCount = 0;
            foreach (($releaseDetail['media'] ?? []) as $medium) {
                $trackCount += count($medium['tracks'] ?? []);
            }

            // Descartar álbumes con menos del mínimo de canciones
            if ($trackCount < $this->minTracksPerAlbum) continue;

            $albumDbId = $this->insertAlbum($releaseDetail, $artistDbId);
            if (!$albumDbId) continue;

            $inserted = $this->insertTracks(
                $releaseDetail, $artistDbId, $albumDbId,
                $country, $genre
            );
            $totalSongsInserted += $inserted;
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  MUSICBRAINZ API
    // ─────────────────────────────────────────────────────────────

    private function searchArtist(string $name): ?string
    {
        $encoded = urlencode($name);
        $data    = $this->mbGet("artist?query=artist:{$encoded}&limit=5&fmt=json");
        if (!$data) return null;

        foreach ($data['artists'] ?? [] as $a) {
            if (strtolower($a['name']) === strtolower($name)) return $a['id'];
        }
        $first = $data['artists'][0] ?? null;
        if ($first && ($first['score'] ?? 0) >= 85) return $first['id'];

        return null;
    }

    private function getArtist(string $mbid): ?array
    {
        return $this->mbGet("artist/{$mbid}?inc=tags+genres+url-rels&fmt=json");
    }

    private function getArtistReleases(string $mbid): array
    {
        $data = $this->mbGet(
            "release?artist={$mbid}&type=album|single&status=official&limit=100&fmt=json"
        );
        if (!$data) return [];

        // Deduplicar por título (quedarse con el más antiguo de cada título)
        $seen   = [];
        $result = [];
        foreach ($data['releases'] ?? [] as $r) {
            $title = strtolower($r['title'] ?? '');
            if (!$title) continue;
            if (!isset($seen[$title])) {
                $seen[$title] = true;
                $result[]     = $r;
            }
        }
        return $result;
    }

    private function getRelease(string $releaseId): ?array
    {
        return $this->mbGet("release/{$releaseId}?inc=recordings+artist-credits&fmt=json", 4, 45);
    }

    // ─────────────────────────────────────────────────────────────
    //  HTTP
    // ─────────────────────────────────────────────────────────────

    private function mbGet(string $endpoint, int $maxRetries = 4, int $timeout = 20): ?array
    {
        $url     = "https://musicbrainz.org/ws/2/{$endpoint}";
        $headers = [
            'User-Agent' => 'SongDleSeeder/1.0 (academic project)',
            'Accept'     => 'application/json',
        ];

        usleep($this->pauseMs * 1000);
        $this->requestCount++;

        for ($try = 1; $try <= $maxRetries; $try++) {
            $r = Http::withHeaders($headers)->timeout($timeout)->get($url);

            if ($r->status() === 503 || $r->status() === 429) {
                $wait = 5 * $try;
                $this->command->newLine();
                $this->command->warn("⏳ Rate limit MB (intento {$try}). Esperando {$wait}s...");
                sleep($wait);
                continue;
            }

            if ($r->successful()) return $r->json();

            $this->command->newLine();
            $this->command->warn("⚠ MB [{$r->status()}]: {$endpoint}");
            return null;
        }

        return null;
    }

    // ─────────────────────────────────────────────────────────────
    //  INSERCIÓN BD
    // ─────────────────────────────────────────────────────────────

    private function insertArtist(array $d, string $fallbackName): ?int
    {
        $nombre   = $d['name'] ?? $fallbackName;
        $existing = DB::table('artists')->where('nombre', $nombre)->value('id_artista');
        if ($existing) return $existing;

        $tags = $d['tags'] ?? $d['genres'] ?? [];

        // MusicBrainz devuelve el país en area.name o begin-area.name, no en 'country'
        $areaName   = $d['area']['name'] ?? $d['begin-area']['name'] ?? $d['country'] ?? null;
        $countryCode = $d['country'] ?? null; // código ISO 2 letras si lo hay
        $country    = $this->resolveCountry($countryCode, $tags, $areaName);
        $genre      = $this->extractGenre($tags);

        $debutYear = (int)($d['life-span']['begin'] ?? now()->year);
        if ($debutYear < 1900 || $debutYear > (int)now()->year) {
            $debutYear = (int)now()->year;
        }

        DB::table('artists')->insert([
            'nombre'            => $nombre,
            'pais'              => $country,
            'genero'            => $genre,
            'debut'             => $debutYear,
            'cantidad_albumes'  => 0,
            'premios'           => $this->awards[$nombre] ?? $this->awards[$fallbackName] ?? 'No disponible',
            'oyentes_mensuales' => $this->monthlyListeners[$nombre] ?? $this->monthlyListeners[$fallbackName] ?? 0,
        ]);

        return DB::table('artists')->where('nombre', $nombre)->value('id_artista');
    }

    private function insertAlbum(array $d, int $artistId): ?int
    {
        $nombre = $d['title'] ?? 'Sin título';

        if (DB::table('albums')->where('nombre', $nombre)->where('id_artista', $artistId)->exists()) {
            return null;
        }

        $date  = $d['date'] ?? null;
        $fecha = $this->parseDate($date);
        $total = 0;
        foreach (($d['media'] ?? []) as $medium) {
            $total += count($medium['tracks'] ?? []);
        }

        // Detectar colaboraciones: MusicBrainz usa 'joinphrase' con 'feat.' entre artistas
        $artistCredits = $d['artist-credit'] ?? [];
        $colaboraciones = false;
        foreach ($artistCredits as $credit) {
            $join = strtolower($credit['joinphrase'] ?? '');
            if (str_contains($join, 'feat') || str_contains($join, 'ft.') || str_contains($join, '&')) {
                $colaboraciones = true;
                break;
            }
        }
        // También si hay más de 1 artista real (excluyendo joinphrases vacíos)
        $realArtists = array_filter($artistCredits, fn($c) => isset($c['artist']));
        if (count($realArtists) > 1) $colaboraciones = true;

        // Reproducciones hardcodeadas por popularidad aproximada
        $reproducciones = rand(1_000_000, 500_000_000);

        $id = DB::table('albums')->insertGetId([
            'nombre'             => $nombre,
            'fecha_lanzamiento'  => $fecha,
            'cantidad_canciones' => $total,
            'colaboraciones'     => $colaboraciones,
            'premios'            => null,
            'reproducciones'     => $reproducciones,
            'id_artista'         => $artistId,
        ]);

        DB::table('artists')->where('id_artista', $artistId)->increment('cantidad_albumes');

        $year = $this->extractYear($date);
        if ($year > 1900) {
            $current = (int) DB::table('artists')->where('id_artista', $artistId)->value('debut');
            if ($year < $current) {
                DB::table('artists')->where('id_artista', $artistId)->update(['debut' => $year]);
            }
        }

        return $id;
    }

    private function insertTracks(array $album, int $artistId, int $albumId, string $country, string $genre): int
    {
        $date  = $album['date'] ?? null;
        $year  = $this->extractYear($date);
        $fecha = $this->parseDate($date);

        $rows  = [];
        $count = 0;

        foreach (($album['media'] ?? []) as $medium) {
            foreach (($medium['tracks'] ?? []) as $track) {
                $titulo = $track['title'] ?? ($track['recording']['title'] ?? null);
                if (!$titulo) continue;

                if (DB::table('songs')->where('titulo', $titulo)->where('id_artista', $artistId)->exists()) {
                    $count++; continue;
                }

                $rows[] = [
                    'titulo'            => $titulo,
                    'pais'              => $country,
                    'anio'              => $year,
                    'genero'            => $genre,
                    'registration_date' => $fecha,
                    'reproducciones'    => rand(500_000, 800_000_000),
                    'id_artista'        => $artistId,
                    'id_album'          => $albumId,
                ];
                $count++;
            }
        }

        if (!empty($rows)) DB::table('songs')->insert($rows);

        return count($rows);
    }

    // ─────────────────────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────────────────────

    /**
     * Convierte un código ISO o nombre de área en nombre completo del país.
     * Si MusicBrainz devuelve un objeto area, usamos area.name directamente.
     */
    private function resolveCountry(?string $code, array $tags, ?string $areaName = null): string
    {
        // 1. Código ISO directo (ej: "PR", "CO", "ES")
        if ($code && strlen($code) === 2 && isset($this->countryCodes[strtoupper($code)])) {
            return $this->countryCodes[strtoupper($code)];
        }

        // 2. area.name de MusicBrainz (ej: "Puerto Rico", "Colombia", "Spain")
        if ($areaName) {
            // Traducir nombres en inglés comunes
            $translations = [
                'Puerto Rico'      => 'Puerto Rico',
                'Colombia'         => 'Colombia',
                'Argentina'        => 'Argentina',
                'Spain'            => 'España',
                'Mexico'           => 'México',
                'Dominican Republic' => 'República Dominicana',
                'United States'    => 'Estados Unidos',
                'Venezuela'        => 'Venezuela',
                'Panama'           => 'Panamá',
                'Cuba'             => 'Cuba',
                'Honduras'         => 'Honduras',
                'Jamaica'          => 'Jamaica',
                'Chile'            => 'Chile',
                'Peru'             => 'Perú',
                'Uruguay'          => 'Uruguay',
                'Brazil'           => 'Brasil',
                'France'           => 'Francia',
                'Germany'          => 'Alemania',
                'Italy'            => 'Italia',
                'United Kingdom'   => 'Reino Unido',
                'Australia'        => 'Australia',
                'Canada'           => 'Canadá',
                'Japan'            => 'Japón',
                'South Korea'      => 'Corea del Sur',
            ];
            if (isset($translations[$areaName])) return $translations[$areaName];
            // Si ya está en español o es reconocible, devolverlo directamente
            if (strlen($areaName) > 2) return $areaName;
        }

        // 3. Inferir de tags
        return $this->guessCountryFromTags($tags);
    }

    private function guessCountryFromTags(array $tags): string
    {
        $names = array_map(fn($t) => strtolower($t['name'] ?? ''), $tags);
        $map   = [
            'reggaeton'    => 'Puerto Rico',
            'latin'        => 'España',
            'trap latino'  => 'Colombia',
            'urbano'       => 'Colombia',
            'flamenco'     => 'España',
            'spanish'      => 'España',
            'argentina'    => 'Argentina',
            'argentine'    => 'Argentina',
            'colombian'    => 'Colombia',
            'puerto rican' => 'Puerto Rico',
            'dominican'    => 'República Dominicana',
            'mexican'      => 'México',
        ];
        foreach ($map as $kw => $country) {
            foreach ($names as $tag) {
                if (str_contains($tag, $kw)) return $country;
            }
        }
        return 'Estados Unidos';
    }

    private function extractGenre(array $tags): string
    {
        if (empty($tags)) return 'Urbano';
        usort($tags, fn($a, $b) => ($b['count'] ?? 0) <=> ($a['count'] ?? 0));
        $names = array_slice(array_column($tags, 'name'), 0, 3);
        return !empty($names) ? implode(', ', $names) : 'Urbano';
    }

    private function parseDate(?string $date): string
    {
        if (!$date) return now()->toDateString();
        try {
            $parts = explode('-', $date);
            return match (count($parts)) {
                3       => Carbon::createFromFormat('Y-m-d', $date)->toDateString(),
                2       => Carbon::createFromFormat('Y-m', $date)->startOfMonth()->toDateString(),
                default => Carbon::createFromFormat('Y', $date)->startOfYear()->toDateString(),
            };
        } catch (\Exception) {
            return now()->toDateString();
        }
    }

    private function extractYear(?string $date): int
    {
        return $date ? (int)substr($date, 0, 4) : (int)now()->year;
    }

    private function printSummary(): void
    {
        $this->command->table(
            ['Tabla', 'Registros'],
            [
                ['artists', DB::table('artists')->count()],
                ['albums',  DB::table('albums')->count()],
                ['songs',   DB::table('songs')->count()],
            ]
        );
        $this->command->info('✅ ¡Seeder completado!');
    }
}