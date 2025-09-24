<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Providers\JwtService;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UnprocessableEntityException;
use Illuminate\Support\Facades\Mail;

class UsuarioController extends Controller
{
    protected $jwt;

    public function __construct(JwtService $jwt)
    {
        $this->jwt = $jwt;
    }

    public function registro(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string',
            'correo' => 'required|email|unique:usuario',
            'contrasenia' => 'required|min:6|confirmed',
        ]);

        $usuario = Usuario::create([
            'nombre' => $datos['nombre'],
            'correo' => $datos['correo'],
            'contrasenia' => Hash::make($datos['contrasenia']),
        ]);

        $token = $this->jwt->crearToken([
            'sub' => $usuario->id
        ]);

        return response()->json([
            'token' => $token,
            'mensaje' => config('messages.usuario.registro_exitoso')
        ], 201);
    }

    public function enviarCorreo(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string',
            'ocupacion' => 'required|string',
            'correo' => 'required|email',
            'edad' => 'required|integer',
        ]);

        $envio = Envio::create([
            'nombre' => $datos['nombre'],
            'ocupacion' => $datos['ocupacion'],
            'correo' => $datos['correo'],
            'edad' => $datos['edad'],
        ]);

        return response()->json([
            'mensaje' => config('messages.envio.exitoso')
        ], 201);
    }

    public function iniciarSesion(Request $request)
    {
        $datos = $request->validate([
            'correo' => 'required|email',
            'contrasenia' => 'required'
        ]);

        $usuario = Usuario::where('correo', $datos['correo'])->first();

        if (!$usuario || !Hash::check($datos['contrasenia'], $usuario->contrasenia)) {
            throw new UnprocessableEntityException(['mensaje' => 'credenciales invalidas']);
        }

        $token = $this->jwt->crearToken([
            'sub' => $usuario->id,
        ]);

        return response()->json([
            'token' => $token,
            'mensaje' => config('messages.usuario.login_exitoso')
        ]);
    }

    public function consultarToken(Request $request)
    {
        return response()->json($request->jwt_user);
    }
}
