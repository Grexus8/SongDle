<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $primaryKey = 'id_song';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'id_artista',
        'id_album',
        'productor',
        'registration_date',
        'pais',
        'anio',
        'genero',
        'reproducciones',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'id_artista', 'id_artista');
    }

    public function album()
    {
        return $this->belongsTo(Album::class, 'id_album', 'id_album');
    }
}