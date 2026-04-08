<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Mostrar listado de álbumes
     */
    public function index(Request $request)
    {
        // 1. Preparamos la consulta base (traer álbumes con sus relaciones)
        $query = Album::with(['artist', 'songs']);

        // 2. Si la URL trae un '?id_artista=', aplicamos el filtro
        if ($request->filled('id_artista')) {
            $query->where('id_artista', $request->id_artista);
        }

        // 3. Ejecutamos la consulta con ->get() y devolvemos el JSON
        return response()->json($query->get());
    }

    /**
     * Guardar un nuevo álbum
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'id_artista' => 'required|exists:artists,id_artista',
            'fecha_lanzamiento' => 'nullable|date',
            'cantidad_canciones' => 'nullable|integer|min:0',
            'colaboraciones' => 'boolean',
            'premios' => 'nullable|string',
            'reproducciones' => 'nullable|integer|min:0',
        ]);

        $album = Album::create($validated);

        return response()->json($album, 201);
    }

    /**
     * Mostrar un álbum específico
     */
    public function show($id)
    {
        return Album::with(['artist', 'songs'])
            ->where('id_album', $id)
            ->firstOrFail();
    }

    /**
     * Actualizar un álbum
     */
    public function update(Request $request, $id)
    {
        $album = Album::where('id_album', $id)->firstOrFail();

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'id_artista' => 'sometimes|required|exists:artists,id_artista',
            'fecha_lanzamiento' => 'nullable|date',
            'cantidad_canciones' => 'nullable|integer|min:0',
            'colaboraciones' => 'boolean',
            'premios' => 'nullable|string',
            'reproducciones' => 'nullable|integer|min:0',
        ]);

        $album->update($validated);

        return response()->json($album);
    }

    /**
     * Eliminar un álbum
     */
    public function destroy($id)
    {
        $album = Album::where('id_album', $id)->firstOrFail();
        $album->delete();

        return response()->json([
            'message' => 'Álbum eliminado correctamente'
        ]);
    }
}
