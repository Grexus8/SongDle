<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    // Listar todos los artistas
    public function index()
    {
        return response()->json(
            Artist::with(['albums', 'songs'])->get()
        );
    }

    // Mostrar un artista específico
    public function show($id)
    {
        $artist = Artist::with(['albums', 'songs'])
                        ->findOrFail($id);

        return response()->json($artist);
    }

    // Guardar un nuevo artista
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
            'genero' => 'required|in:hombre,mujer',
            'debut' => 'required|integer|min:1900|max:' . date('Y'),
            'cantidad_albumes' => 'required|integer|min:0',
            'premios' => 'nullable|string|max:255',
            'oyentes_mensuales' => 'required|integer|min:0',
        ]);

        $artist = Artist::create($validated);

        return response()->json([
            'message' => 'Artista creado correctamente',
            'artist' => $artist
        ], 201);
    }

    // Actualizar un artista existente
    public function update(Request $request, $id)
    {
        $artist = Artist::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'pais' => 'sometimes|string|max:255',
            'genero' => 'sometimes|in:hombre,mujer',
            'debut' => 'sometimes|integer|min:1900|max:' . date('Y'),
            'cantidad_albumes' => 'sometimes|integer|min:0',
            'premios' => 'nullable|string|max:255',
            'oyentes_mensuales' => 'sometimes|integer|min:0',
        ]);

        $artist->update($validated);

        return response()->json([
            'message' => 'Artista actualizado correctamente',
            'artist' => $artist
        ]);
    }

    // Eliminar un artista
    public function destroy($id)
    {
        $artist = Artist::findOrFail($id);
        $artist->delete();

        return response()->json([
            'message' => 'Artista eliminado correctamente'
        ]);
    }
}
