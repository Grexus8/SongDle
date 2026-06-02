<?php

namespace App\Http\Controllers;

use App\Models\ArcadeRanking;
use Illuminate\Http\Request;

class ArcadeController extends Controller
{
    public function obtenerRecordUsuario($id)
{
    // 1. Buscamos el récord del usuario
    $record = ArcadeRanking::where('id_usuario', $id)->first();

    // Si el usuario no ha jugado nunca, no tiene puesto
    if (!$record || $record->puntos_maximos == 0) {
        return response()->json([
            'id_usuario' => (int)$id,
            'puntos_maximos' => 0,
            'canciones_adivinadas_max' => 0,
            'puesto' => '---'
        ], 200);
    }

    // 2. Contamos cuántos usuarios tienen más puntos que él
    $usuariosPorEncima = ArcadeRanking::where('puntos_maximos', '>', $record->puntos_maximos)->count();
    $puestoFisico = $usuariosPorEncima + 1;

    // 3. Si el puesto es mayor que 100, guardamos '---', si no, el número
    $puestoFinal = $puestoFisico > 100 ? '---' : $puestoFisico;

    return response()->json([
        'id_usuario' => $record->id_usuario,
        'puntos_maximos' => $record->puntos_maximos,
        'canciones_adivinadas_max' => $record->canciones_adivinadas_max,
        'puesto' => $puestoFinal
    ], 200);
}
    public function obtenerRankingPuntos(){
        $ranking = ArcadeRanking::with('user') 
            ->orderBy('puntos_maximos', 'desc') ->take(10) ->get();

        return response()->json($ranking, 200);
    }
    public function obtenerRankingCanciones(){
        $ranking = ArcadeRanking::with('user') 
            ->orderBy('canciones_adivinadas_max', 'desc') ->take(10) ->get();

        return response()->json($ranking, 200);
    }
    public function guardarPartida(Request $request, $id)
    {
        // 1. Validamos los datos que llegan desde Vue
        $validacion = $request->validate([
            'puntos_totales' => 'required|integer|min:0',
            'canciones_adivinadas' => 'required|integer|min:0'
        ]);

        // 2. Buscamos el récord del usuario por su ID (Primer array).
        // Si no existe, lo creamos asignando directamente los puntos de esta partida (Segundo array).
        $registro = ArcadeRanking::firstOrCreate(
            ['id_usuario' => $id], 
            [
                'puntos_maximos' => $validacion['puntos_totales'],
                'canciones_adivinadas_max' => $validacion['canciones_adivinadas']
            ]
        );

        // Chivato para saber si tenemos que actualizar la base de datos al final
        $actualizado = false;

        // 3. Comprobamos si ha superado su récord histórico de puntos
        if ($validacion['puntos_totales'] > $registro->puntos_maximos) {
            $registro->puntos_maximos = $validacion['puntos_totales'];
            $actualizado = true;
        }

        // 4. Comprobamos de forma independiente si ha superado su récord de canciones
        if ($validacion['canciones_adivinadas'] > $registro->canciones_adivinadas_max) {
            $registro->canciones_adivinadas_max = $validacion['canciones_adivinadas'];
            $actualizado = true;
        }

        // 5. Si ha superado alguna de las dos cosas (o ambas), guardamos los cambios
        if ($actualizado) {
            $registro->save();
        }

        // 6. Devolvemos la respuesta al frontend
        return response()->json([
            'mensaje' => 'Partida finalizada procesada',
            'nuevo_record' => $actualizado,
            'datos' => $registro
        ], 200);
    }
}