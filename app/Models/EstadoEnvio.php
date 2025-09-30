<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoEnvio extends Model
{
    protected $table = "estado_envio";

    protected $fillable = [
        'id',
        'nombre'
    ];

    public $timestamps = false;
}
