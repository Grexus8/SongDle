<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users'; 
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'profile_img',
        'administrador',
        'registration_date',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'administrador' => 'boolean',
        'registration_date' => 'date',
        'password' => 'hashed',
    ];
 
    public function estadistica()
    {
        return $this->hasOne(Estadistica::class, 'id_usuario', 'id_usuario');
    }
}