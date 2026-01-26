<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Estadistica extends Model
{
    protected $table = 'estadisticas';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'partidas_jugadas',
        'partidas_ganadas',
        'racha',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}