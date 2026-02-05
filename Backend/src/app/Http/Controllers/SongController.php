<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    /**
     * Listar canciones
     */
    public function index()
    {
        return Song::with(['artist', 'album'])->get();
    }

    /**
     * Guardar una nueva canción
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'id_artista' => 'required|exists:artists,id_artista',
            'id_album' => 'nullable|exists:albums,id_album',
            'productor' => 'nullable|string|max:255',
            'registration_date' => 'nullable|date',
            'pais' => 'nullable|string|max:100',
            'anio' => 'nullable|integer|min:1900|max:' . date('Y'),
            'genero' => 'nullable|string|max:100',
            'reproducciones' => 'nullable|integer|min:0',
        ]);

        $song = Song::create($validated);

        return response()->json($song, 201);
    }

    /**
     * Mostrar una canción específica
     */
    public function show($id)
    {
        return Song::with(['artist', 'album'])
            ->where('id_song', $id)
            ->firstOrFail();
    }

    /**
     * Actualizar una canción
     */
    public function update(Request $request, $id)
    {
        $song = Song::where('id_song', $id)->firstOrFail();

        $validated = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'id_artista' => 'sometimes|required|exists:artists,id_artista',
            'id_album' => 'nullable|exists:albums,id_album',
            'productor' => 'nullable|string|max:255',
            'registration_date' => 'nullable|date',
            'pais' => 'nullable|string|max:100',
            'anio' => 'nullable|integer|min:1900|max:' . date('Y'),
            'genero' => 'nullable|string|max:100',
            'reproducciones' => 'nullable|integer|min:0',
        ]);

        $song->update($validated);

        return response()->json($song);
    }

    /**
     * Eliminar una canción
     */
    public function destroy($id)
    {
        $song = Song::where('id_song', $id)->firstOrFail();
        $song->delete();

        return response()->json([
            'message' => 'Canción eliminada correctamente'
        ]);
    }
}
