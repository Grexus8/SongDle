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
            'debut' => 'required|integer',
            'cantidad_albumes' => 'required|integer',
            'premios' => 'required|integer',
            'oyentes_mensuales' => 'required|integer',
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
            'debut' => 'sometimes|integer',
            'cantidad_albumes' => 'sometimes|integer',
            'premios' => 'sometimes|integer',
            'oyentes_mensuales' => 'sometimes|integer',
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
