<?php

namespace App\Http\Controllers;

use App\Models\Estadistica;
use Illuminate\Http\Request;

class EstadisticaController extends Controller
{
    public function index()
    {
        return Estadistica::with('user')->get();
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'id_usuario'        => 'required|integer',
        'modo_juego'        => 'required|string|in:cancion,album,artista',
        'partidas_jugadas'  => 'required|integer|min:0',
        'partidas_ganadas'  => 'required|integer|min:0',
        'historial_intentos'=> 'nullable|array',
    ]);

    $estadistica = Estadistica::updateOrCreate(
        [
            'id_usuario' => $validated['id_usuario'],
            'modo_juego' => $validated['modo_juego']
        ],
        [
            'partidas_jugadas'   => $validated['partidas_jugadas'],
            'partidas_ganadas'   => $validated['partidas_ganadas'],
            'historial_intentos' => $validated['historial_intentos'] ?? []
        ]
    );

    return response()->json($estadistica, 200);
}

    public function porUsuario($id_usuario)
    {
        return Estadistica::where('id_usuario', $id_usuario)->get();
    }
}