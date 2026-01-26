<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $primaryKey = 'id_artista';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'pais',
        'debut',
        'cantidad_albumes',
        'premios',
        'oyentes_mensuales',
    ];

    public function albums()
    {
        return $this->hasMany(Album::class, 'id_artista', 'id_artista');
    }

    public function songs()
    {
        return $this->hasMany(Song::class, 'id_artista', 'id_artista');
    }
}