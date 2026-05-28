<?php

namespace App\Http\Controllers;

use App\Models\ArcadeRanking;
use Illuminate\Http\Request;

class ArcadeController extends Controller
{
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