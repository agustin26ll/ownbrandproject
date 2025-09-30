<?php

namespace App\Enums;

enum EstadoEnvio: int
{
    case PREPARADO = 1;
    case ENVIADO = 2;
    case RECIBIDO = 3;
}
