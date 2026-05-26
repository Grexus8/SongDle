<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SpotifySeeder extends Seeder
{
    private string $accessToken = '';

    // ─────────────────────────────────────────────
    //  ARTISTAS — formato mixto:
    //    ID directo de 22 chars  → usado directamente
    //    'search:Nombre'         → buscado por nombre en la API
    // ─────────────────────────────────────────────
    private array $artistIds = [
        // Urbano Latino / Reggaeton
        '4q3ewBCX7sLwd24euuV69X', // Bad Bunny
        '4VMYDCV2IEDYJArk749S6m', // Daddy Yankee
        '1vyhD5VmyZ7KMfW5gqLgo5', // J Balvin
        '790FomKkXshlbRYZFtlgla', // Karol G
        '1i8SpTcr7yvPOmcqrbnVXY', // Ozuna
        '37230BxxYs9ksS7OkZw3IU', // Chencho Corleone
        '1mcTU81TzQhprhouKaTkpq', // Rauw Alejandro
        '2LRoIwlKmHjgvigdNGBHNo', // Feid
        '7iK8PXO48WeuP03g8YR51W', // Myke Towers
        '5XJDexmWFLWOkjOEjOVX3e', // Eladio Carrion
        '6nVcHLIgY5pE2YCl8ubca1', // Jhayco
        '0Q8NcsJwoCbZOHHW63su5S', // Mora
        '4SsVbpTthjScTS7U2hmr1X', // Arcangel
        '6w9ToX5slZ4uIdmD17hJ3c', // Bryant Myers
        '1r4hJ1h58CWwUQe3MxPuau', // Maluma
        // España
        '52iwsT98xCoGgiGntTiR7K', // Quevedo
        '4az97MtWmBQ5Db3GfDh9j9', // Morad
        '2O8vbr4RYPpk6MRA4fio7u', // Saiko
        '2IMZYfNi21MGqxopj9fWx8', // Rels B
        '1rTUwYS38LkQTlT2fhikch', // Yung Beef
        '0jeYkqwckGJoHQhhXwgzk3', // Cruz Cafuné
        '4bJh7sMPcVRiqe5jlnsWQV', // Dellafuente
        '4nXXIxTneJksvGXrlmX8oA', // Kaydy Cain
        '5Ypafuz95Xk09YDf4tgAvU', // Ayax y Prok
        '1QJbbsxg2wqidJj51d3otw', // Natos y Waor
        '4bWHA8fMNjzfGPQqnh5D6y', // Recycled J
        '5UN0rzL594mWY2RbOtZqIN', // Ptazeta
        '3lY9Fxceu60W1rbon7PkuF', // Omar Montes
        '0FwnPHExlRRxEZPLAi5tmG', // JC Reyes
        '2CCgb0KApjfQDuTppovpf8', // Rvfv
        '4F4pp8NUW08JuXwnoxglpN', // Bad Gyal
        '3bvfu2KAve4lPHrhEFDZna', // Lola Indigo
        // Argentina
        '1bAftSH8umNcGZ0uyV7LMg', // Duki
        '716NhGYqD1jl2wI1Qkgq36', // Bizarrap
        '2x7PC78TmgqpEIjaGAZ0Oz', // Trueno
        '2UZIAOlrnyZmyzt1nuXr9y', // Nicki Nicole
        '4m6ubhNsdwF4psNf3R8kwR', // KHEA
        '1vqR17Iv8VFdzure1TAXEq', // LIT killah
        '2qWK8K2Jfh67UqtwY8tCW6', // YSY A
        '5Y3MV9DZ0d87NnVm56qSY1', // Tiago PZK
        '5YCc6xS5Gpj3EkaYGdjyNK', // WOS
        '19HM5j0ULGSmEoRcrSe5x3', // Milo J
        '3vQ0GE3mI0dAaxIMYe5g7z', // Paulo Londra
        '6w3SkAHYPsQ1bxV7VDlG5y', // Cazzu
        '1DxLCyH42yaHKGK3cl5bvG', // Maria Becerra
        // España / Internacional
        '7ltDVBr6mKbRvohxheJ9h1', // Rosalia
        '5TYxZTjIPqKM8K8NuP9woO', // C. Tangana
        '3qsKSpcV3ncke3hw52JSMB', // Young Miko
        // Puerto Rico / Caribe
        '2R21vXR83lH98kGeO99Y66', // Anuel AA
        '33ScadVnbm2X8kkUqOkC6Z', // Don Omar
        '1SupJlEpv7RS2tPNRaHViT', // Nicky Jam
        '77ziqFxp5gaInVrF2lj4ht', // Sech
        '1wZtkThiXbVNtj6hee6dz9', // Wisin & Yandel
        '21451j1KhjAiaYKflxBjr1', // Zion & Lennox
        '329e4yvIujISKGKz1BZZbO', // Farruko
        '14zUHaJZo1mnYtn6IBRaRP', // Justin Quiles
        '0KPX4Ucy9dk82uj4GpKesn', // Dalex
        '11zcx4T53n2zYVnh62Kdu7', // Lenny Tavarez
        '5hdhHgpxyniooUiQVaPxQ0', // Nio Garcia
        '2oQX8QiMXOyuqbcZEFsZfm', // El Alfa
    ];

    // ─────────────────────────────────────────────
    //  CONFIGURACIÓN
    // ─────────────────────────────────────────────
    private int    $maxAlbumsPerArtist = 3;
    private int    $maxTracksPerAlbum  = 10;
    private string $albumTypes         = 'album,single';
    private string $market             = 'ES';

    // ─────────────────────────────────────────────

    public function run(): void
    {
        $this->command->info('🎵 Iniciando Spotify Seeder...');

        $this->accessToken = $this->getAccessToken();
        if (!$this->accessToken) {
            $this->command->error('❌ No se pudo obtener el token. Revisa config/services.php');
            return;
        }
        $this->command->info('✅ Token obtenido.');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('songs')->truncate();
        DB::table('albums')->truncate();
        DB::table('artists')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('🗑  Tablas limpiadas.');

        // ── 1. Resolver search:Nombre → ID ──
        $this->command->info('🔍 Resolviendo nombres...');
        $allIds   = $this->resolveArtistIds($this->artistIds);
        $uniqueIds = array_values(array_unique(array_filter($allIds)));
        $total    = count($uniqueIds);
        $this->command->info("👥 {$total} artistas a importar.");

        // ── 2. Descargar datos de artistas en batch de 50 ──
        $this->command->info('📡 Descargando artistas...');
        $artistsData = $this->fetchArtistsBatch($uniqueIds);
        $this->command->info('   ✓ ' . count($artistsData) . ' artistas recibidos de Spotify.');

        if (empty($artistsData)) {
            $this->command->error('❌ Spotify no devolvió ningún artista. Revisa los IDs y el token.');
            return;
        }

        // ── 3. Descargar lista de álbumes por artista ──
        $this->command->info('💿 Descargando listas de álbumes...');
        $bar = $this->command->getOutput()->createProgressBar(count($artistsData));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%%');
        $bar->start();

        // artistDbId => [debut_year, albums[]]
        $artistMeta    = [];   // spotifyId => ['db_id', 'country', 'genre']
        $albumsToFetch = [];   // spotifyAlbumId => ['artist_db_id', 'country', 'genre']

        foreach ($artistsData as $artistData) {
            $artistDbId = $this->insertArtist($artistData);
            if (!$artistDbId) { $bar->advance(); continue; }

            $country = $this->guessCountry($artistData);
            $genre   = $this->formatGenres($artistData['genres'] ?? []);

            // Álbumes del artista (limit directo = maxAlbumsPerArtist, sin paginación)
            $albumItems = $this->getArtistAlbums($artistData['id']);

            foreach ($albumItems as $album) {
                $albumsToFetch[$album['id']] = [
                    'artist_db_id' => $artistDbId,
                    'country'      => $country,
                    'genre'        => $genre,
                ];
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();

        // ── 4. Descargar álbumes completos en batch de 20 y guardar tracks ──
        $albumIds = array_keys($albumsToFetch);
        $total    = count($albumIds);
        $this->command->info("🎵 Descargando {$total} álbumes completos y canciones...");

        $barA = $this->command->getOutput()->createProgressBar(count(array_chunk($albumIds, 20)));
        $barA->setFormat(' %current%/%max% [%bar%] %percent:3s%%');
        $barA->start();

        foreach (array_chunk($albumIds, 20) as $chunk) {
            $albumsData = $this->fetchAlbumsBatch($chunk);

            foreach ($albumsData as $albumFull) {
                $meta      = $albumsToFetch[$albumFull['id']] ?? null;
                if (!$meta) continue;

                $albumDbId = $this->insertAlbum($albumFull, $meta['artist_db_id']);
                if (!$albumDbId) continue;

                $this->insertTracks($albumFull, $meta, $albumDbId);
            }

            $barA->advance();
        }

        $barA->finish();
        $this->command->newLine(2);
        $this->printSummary();
    }

    // ──────────────────────────────────────────────────────────────
    //  AUTH
    // ──────────────────────────────────────────────────────────────

    private function getAccessToken(): string
    {
        $clientId     = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');

        if (!$clientId || !$clientSecret) {
            $this->command->error('Faltan SPOTIFY_CLIENT_ID / SPOTIFY_CLIENT_SECRET en config/services.php');
            return '';
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post('https://accounts.spotify.com/api/token', ['grant_type' => 'client_credentials']);

        if (!$response->successful()) {
            $this->command->error('Error auth Spotify: ' . $response->body());
            return '';
        }

        return $response->json('access_token', '');
    }

    // ──────────────────────────────────────────────────────────────
    //  RESOLUCIÓN IDs
    // ──────────────────────────────────────────────────────────────

    private function resolveArtistIds(array $entries): array
    {
        $resolved = [];
        $searches = [];

        // Separar IDs directos de búsquedas
        foreach ($entries as $entry) {
            if (str_starts_with($entry, 'search:')) {
                $searches[] = substr($entry, 7);
            } else {
                $resolved[] = $entry;
            }
        }

        // Resolver búsquedas — pausa entre requests para no golpear el rate limit
        foreach ($searches as $name) {
            usleep(300_000); // 300ms entre búsquedas
            $id = $this->searchArtistByName($name);
            if ($id) {
                $resolved[] = $id;
                $this->command->line("   ✓ '{$name}' → {$id}");
            } else {
                $this->command->warn("   ⚠ No encontrado: '{$name}'");
            }
        }

        return $resolved;
    }

    private function searchArtistByName(string $name): ?string
    {
        $data  = $this->spotifyGet('search?q=' . urlencode($name) . '&type=artist&limit=5&market=' . $this->market);
        $items = $data['artists']['items'] ?? [];

        // Coincidencia exacta primero
        foreach ($items as $item) {
            if (strtolower($item['name']) === strtolower($name)) {
                return $item['id'];
            }
        }

        // Primer resultado si hay similitud razonable
        if (!empty($items)) {
            similar_text(strtolower($items[0]['name']), strtolower($name), $pct);
            if ($pct >= 70) return $items[0]['id'];
        }

        return null;
    }

    // ──────────────────────────────────────────────────────────────
    //  SPOTIFY API — BATCH
    // ──────────────────────────────────────────────────────────────

    /** 1 request por artista (el batch /artists?ids= está desactivado en Development mode) */
    private function fetchArtistsBatch(array $ids): array
    {
        $result = [];
        foreach ($ids as $id) {
            $data = $this->spotifyGet("artists/{$id}");
            if ($data && isset($data['id'])) {
                $result[] = $data;
            }
            usleep(500_000); // 500ms entre requests
        }
        return $result;
    }

    /** 1 request por álbum (el batch /albums?ids= está desactivado en Development mode) */
    private function fetchAlbumsBatch(array $ids): array
    {
        $result = [];
        foreach ($ids as $id) {
            $data = $this->spotifyGet("albums/{$id}?market={$this->market}");
            if ($data && isset($data['id'])) {
                $result[] = $data;
            }
            usleep(500_000); // 500ms entre requests
        }
        return $result;
    }

    private function getArtistAlbums(string $artistId): array
    {
        // Pedimos más álbumes de los que necesitamos para poder encontrar el más antiguo
        // y calcular el debut correctamente, luego limitamos al insertar
        $limit = max($this->maxAlbumsPerArtist * 3, 10);
        $data  = $this->spotifyGet(
            "artists/{$artistId}/albums?include_groups={$this->albumTypes}&market={$this->market}&limit=50"
        );
        $items = $data['items'] ?? [];

        if (empty($items)) return [];

        // Ordenar por fecha ASC para que el más antiguo quede primero
        usort($items, fn($a, $b) => strcmp($a['release_date'] ?? '', $b['release_date'] ?? ''));

        // Guardamos el más antiguo (para el debut) + los más recientes hasta el límite
        $oldest = $items[0];
        $recent = array_slice(array_reverse($items), 0, $this->maxAlbumsPerArtist - 1);

        // Combinamos: el más antiguo + los más recientes (sin duplicados)
        $selected = collect([$oldest, ...$recent])
            ->unique('id')
            ->values()
            ->toArray();

        return $selected;
    }

    // ──────────────────────────────────────────────────────────────
    //  INSERCIÓN BD
    // ──────────────────────────────────────────────────────────────

    private function insertArtist(array $data): ?int
    {
        $nombre = $data['name'];

        $existing = DB::table('artists')->where('nombre', $nombre)->value('id_artista');
        if ($existing) return $existing;

        // Debut: inicializamos con el año actual, se actualizará con la canción más antigua
        $debut = (int) now()->year;

        DB::table('artists')->insert([
            'nombre'            => $nombre,
            'pais'              => $this->guessCountry($data),
            'genero'            => $this->formatGenres($data['genres'] ?? []),
            'debut'             => $debut,
            'cantidad_albumes'  => 0,
            'premios'           => 'No disponible',
            'oyentes_mensuales' => $data['followers']['total'] ?? 0,
        ]);

        return DB::table('artists')->where('nombre', $nombre)->value('id_artista');
    }

    private function insertAlbum(array $data, int $artistId): ?int
    {
        $exists = DB::table('albums')
            ->where('nombre', $data['name'])
            ->where('id_artista', $artistId)
            ->exists();
        if ($exists) return null;

        $fecha = $this->parseReleaseDate($data['release_date'] ?? null, $data['release_date_precision'] ?? 'year');

        $id = DB::table('albums')->insertGetId([
            'nombre'             => $data['name'],
            'fecha_lanzamiento'  => $fecha,
            'cantidad_canciones' => $data['total_tracks'] ?? 0,
            'colaboraciones'     => count($data['artists'] ?? []) > 1,
            'premios'            => null,
            'reproducciones'     => ($data['popularity'] ?? 0) * 1_000_000,
            'id_artista'         => $artistId,
        ]);

        // El debut se actualiza en insertTracks con la canción más antigua

        DB::table('artists')->where('id_artista', $artistId)->increment('cantidad_albumes');

        return $id;
    }

    private function insertTracks(array $albumData, array $meta, int $albumDbId): void
    {
        $tracks  = $albumData['tracks']['items'] ?? [];
        $year    = $this->extractYear($albumData['release_date'] ?? null);
        $date    = $this->parseReleaseDate($albumData['release_date'] ?? null, $albumData['release_date_precision'] ?? 'year');

        $rows  = [];
        $count = 0;

        foreach ($tracks as $track) {
            if ($count >= $this->maxTracksPerAlbum) break;

            $exists = DB::table('songs')
                ->where('titulo', $track['name'])
                ->where('id_artista', $meta['artist_db_id'])
                ->exists();
            if ($exists) { $count++; continue; }

            $rows[] = [
                'titulo'            => $track['name'],
                'productor'         => null,
                'pais'              => $meta['country'],
                'anio'              => $year,
                'genero'            => $meta['genre'],
                'registration_date' => $date,
                'reproducciones'    => rand(500_000, 800_000_000),
                'id_artista'        => $meta['artist_db_id'],
                'id_album'          => $albumDbId,
            ];
            $count++;
        }

        if (!empty($rows)) {
            DB::table('songs')->insert($rows);
        }

        // Actualizar debut del artista si alguna canción de este álbum es más antigua
        $albumYear = $this->extractYear($albumData['release_date'] ?? null);
        if ($albumYear > 1900 && $albumYear <= (int) now()->year) {
            $currentDebut = (int) DB::table('artists')
                ->where('id_artista', $meta['artist_db_id'])
                ->value('debut');
            if ($albumYear < $currentDebut) {
                DB::table('artists')
                    ->where('id_artista', $meta['artist_db_id'])
                    ->update(['debut' => $albumYear]);
            }
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  HTTP
    // ──────────────────────────────────────────────────────────────

    private function spotifyGet(string $endpoint, int $maxRetries = 5): ?array
    {
        $url = "https://api.spotify.com/v1/{$endpoint}";

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            $response = Http::withToken($this->accessToken)->timeout(15)->get($url);

            if ($response->status() === 401) {
                $this->accessToken = $this->getAccessToken();
                continue;
            }

            if ($response->status() === 429) {
                $retryAfter = (int)($response->header('Retry-After') ?? 5);
                if ($retryAfter > 3600) {
                    $retryAfter = max(1, $retryAfter - time());
                }
                $wait = min($retryAfter + 1, 60);
                $this->command->warn("\n⏳ Rate limit (intento {$attempt}/{$maxRetries}). Esperando {$wait}s...");
                sleep($wait);
                continue;
            }

            if ($response->successful()) {
                return $response->json();
            }

            // Otro error — no reintentar
            $this->command->warn("\n⚠ Spotify [{$response->status()}]: {$endpoint}");
            return null;
        }

        $this->command->warn("\n⚠ Máximos reintentos alcanzados: {$endpoint}");
        return null;
    }

    // ──────────────────────────────────────────────────────────────
    //  HELPERS
    // ──────────────────────────────────────────────────────────────

    private function parseReleaseDate(?string $date, string $precision): string
    {
        if (!$date) return now()->toDateString();
        try {
            return match ($precision) {
                'day'   => Carbon::createFromFormat('Y-m-d', $date)->toDateString(),
                'month' => Carbon::createFromFormat('Y-m', $date)->startOfMonth()->toDateString(),
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

    private function formatGenres(array $genres): string
    {
        return !empty($genres) ? implode(', ', array_slice($genres, 0, 3)) : 'Desconocido';
    }

    private function guessCountry(array $artistData): string
    {
        $genres = array_map('strtolower', $artistData['genres'] ?? []);

        $map = [
            'reggaeton'   => 'Puerto Rico',
            'trap latino' => 'Colombia',
            'latin'       => 'España',
            'urbano latino' => 'Colombia',
            'corrido'     => 'México',
            'british'     => 'Reino Unido',
            'australian'  => 'Australia',
            'canadian'    => 'Canadá',
            'french'      => 'Francia',
            'k-pop'       => 'Corea del Sur',
            'flamenco'    => 'España',
            'spanish'     => 'España',
            'drill espanol' => 'España',
        ];

        foreach ($map as $keyword => $country) {
            foreach ($genres as $genre) {
                if (str_contains($genre, $keyword)) return $country;
            }
        }

        return 'Estados Unidos';
    }

    private function printSummary(): void
    {
        $this->command->table(
            ['Tabla', 'Registros insertados'],
            [
                ['artists', DB::table('artists')->count()],
                ['albums',  DB::table('albums')->count()],
                ['songs',   DB::table('songs')->count()],
            ]
        );
        $this->command->info('✅ ¡Seeder completado!');
    }
}