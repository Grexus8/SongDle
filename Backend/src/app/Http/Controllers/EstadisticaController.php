<?php

namespace App\Http\Controllers;

use App\Models\Estadistica;
use Illuminate\Http\Request;

class EstadisticaController extends Controller
{
    /**
     * Listar estadísticas
     */
    public function index()
    {
        return Estadistica::with('user')->get();
    }

    /**
     * Guardar una nueva estadística
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:users,id_usuario',
            'partidas_jugadas' => 'required|integer|min:0',
            'partidas_ganadas' => 'required|integer|min:0',
            'racha' => 'required|integer|min:0',
        ]);

        $estadistica = Estadistica::create($validated);

        return response()->json($estadistica, 201);
    }

    /**
     * Mostrar una estadística específica
     */
    public function show($id)
    {
        return Estadistica::with('user')->findOrFail($id);
    }

    /**
     * Actualizar una estadística
     */
    public function update(Request $request, $id)
    {
        $estadistica = Estadistica::findOrFail($id);

        $validated = $request->validate([
            'id_usuario' => 'sometimes|required|exists:users,id_usuario',
            'partidas_jugadas' => 'sometimes|required|integer|min:0',
            'partidas_ganadas' => 'sometimes|required|integer|min:0',
            'racha' => 'sometimes|required|integer|min:0',
        ]);

        $estadistica->update($validated);

        return response()->json($estadistica);
    }

    /**
     * Eliminar una estadística
     */
    public function destroy($id)
    {
        $estadistica = Estadistica::findOrFail($id);
        $estadistica->delete();

        return response()->json([
            'message' => 'Estadística eliminada correctamente'
        ]);
    }
}
