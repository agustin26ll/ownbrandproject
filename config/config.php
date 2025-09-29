<?php

return [
    'label_mensaje' => env('LABEL_MENSAJE', 'mensaje'),
    'jwt_secret' => env('JWT_SECRET'),
    'jwt_algoritmo' => env('JWT_ALGORITHM', 'HS256'),
    'cors_allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
];
