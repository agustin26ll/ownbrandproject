<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = "envio";

    protected $fillable = [
        'id',
        'nombre',
        'correo',
        'edad',
        'ocupacion',
    ];

    public $timestamps = false;
}
