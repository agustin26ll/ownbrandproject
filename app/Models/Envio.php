<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = "envio";

    protected $fillable = [
        'id',
        'id_usuario',
        'id_frecuencia',
        'nombre',
        'correo',
        'edad',
        'ocupacion'
    ];

    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
