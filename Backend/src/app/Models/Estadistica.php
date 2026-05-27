<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estadistica extends Model
{
    protected $table = 'estadisticas';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'modo_juego',
        'partidas_jugadas',
        'partidas_ganadas',
        'historial_intentos',
    ];

    protected $casts = [
        'historial_intentos' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}