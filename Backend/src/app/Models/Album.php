<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $primaryKey = 'id_album';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'id_artista',
        'fecha_lanzamiento',
        'cantidad_canciones',
        'colaboraciones',
        'premios',
        'reproducciones',
    ];

    protected $casts = [
        'colaboraciones' => 'boolean',
        'fecha_lanzamiento' => 'date',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'id_artista', 'id_artista');
    }

    public function songs()
    {
        return $this->hasMany(Song::class, 'id_album', 'id_album');
    }
}