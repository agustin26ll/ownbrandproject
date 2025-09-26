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
        'edad'
    ];

    protected $hidden = [
        'contrasenia',
    ];

    public $timestamps = false;

    public function envios()
    {
        return $this->hasMany(Envio::class, 'id_usuario');
    }

    public function cajas()
    {
        return $this->hasMany(Caja::class, 'id_usuario');
    }
}
