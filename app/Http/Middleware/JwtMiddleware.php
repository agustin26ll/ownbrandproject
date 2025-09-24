<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Providers\JwtService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    protected $jwt;

    public function __construct(JwtService $jwt)
    {
        $this->jwt = $jwt;
    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->bearerToken();
            
            if (!$token) {
                return response()->json(['error' => 'Token no encontrado'], 401);
            }

            $decoded = $this->jwt->verificarToken($token);

            $request->merge(['jwt_user' => $decoded]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        return $next($request);
    }
}
