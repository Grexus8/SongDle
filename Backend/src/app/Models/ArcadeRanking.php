<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArcadeRanking extends Model
{
    protected $table = 'arcade_rankings';
    
    protected $primaryKey = 'id_ranking';

    protected $fillable = [
        'id_usuario',
        'puntos_maximos',
        'canciones_adivinadas_max'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}