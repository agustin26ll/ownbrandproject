<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = "envio";

    protected $fillable = [
        'id',
        'id_usuario',
        'id_estado',
        'id_frecuencia',
        'nombre',
        'correo',
        'edad',
        'ocupacion',
        'direccion'
    ];

    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoEnvio::class, 'id_estado');
    }
}
