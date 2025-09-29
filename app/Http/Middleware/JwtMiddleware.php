<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Providers\JwtService;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    protected $jwtService;
    private $mensajesAuth;
    private $mensajesUsuario;
    private $labelMensaje;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
        $this->mensajesAuth = config('messages.autenticacion');
        $this->mensajesUsuario = config('messages.usuario');
        $this->labelMensaje = config('config.label_mensaje');
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Api-Token');

        if (!$token) {
            return response()->json([$this->labelMensaje => $this->mensajesAuth['no_proporcionado']], 401);
        }

        try {
            $payload = $this->jwtService->validate($token);

            if (!$payload || !isset($payload['sub'])) {
                return response()->json([$this->labelMensaje => $this->mensajesAuth['expirado']], 401);
            }

            $usuario = Usuario::find($payload['sub']);

            if (!$usuario) {
                return response()->json([$this->labelMensaje => $this->mensajesUsuario['no_encontrado']], 401);
            }

            Auth::setUser($usuario);
            $request->attributes->set('jwt_payload', $usuario);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([$this->labelMensaje => $this->mensajesAuth['invalido']], 401);
        }
    }
}
