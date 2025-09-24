<?php

namespace App\Providers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class JwtService extends ServiceProvider
{
    protected $claveSecreta;
    protected $expiracion;
    protected $tokenRefrescado;

    public function __construct()
    {
        $this->claveSecreta = env('JWT_SECRET');
        $this->expiracion = intval(env('JWT_TTL', 3600));
        $this->tokenRefrescado = intval(env('JWT_REFRESH_TTL', 86400));
    }

    public function crearToken(array $claims = []): string
    {
        $hora = time();

        $payload = array_merge([
            'iss' => config('app.url'),
            'iat' => $hora,
            'nbf' => $hora,
            'exp' => $hora + $this->expiracion,
            'jti' => Str::uuid()->toString(),
        ], $claims);

        return JWT::encode($payload, $this->claveSecreta, 'HS256');
    }

    public function verificarToken(string $token): object
    {
        return JWT::decode($token, new Key($this->claveSecreta, 'HS256'));
    }
}
