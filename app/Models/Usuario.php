<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Usuario extends Model
{
    use AuthenticatableTrait;

    protected $table = "usuario";

    protected $fillable = [
        'id',
        'nombre',
        'correo',
        'contrasenia',
        'edad',
        'ocupacion',
    ];

    protected $hidden = [
        'contrasenia',
    ];

    public $timestamps = false;
}
