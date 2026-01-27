<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class user extends Model
{
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'administrador',
    ];

    protected $hidden = [
        'password',
        'administrador',
    ];
    protected $casts = [
        'administrador' => 'boolean',
    ];

    public function estadistica()
    {
        return $this->hasOne(Estadistica::class, 'id_usuario', 'id_usuario');
    }
}