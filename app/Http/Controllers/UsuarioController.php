<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Providers\JwtService;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UnprocessableEntityException;
use App\Mail\EnvioProductosMail;
use App\Models\Caja;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'correo' => 'required|email',
            'contrasenia' => 'required|min:6',
            'edad' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $usuarioExistente = Usuario::where('correo', $datos['correo'])->exists();

            if ($usuarioExistente) {
                return response()->json([
                    'mensaje' => config('messages.usuario.email_existente')
                ], 422);
            }

            $usuario = Usuario::create([
                'nombre' => $datos['nombre'],
                'correo' => $datos['correo'],
                'contrasenia' => Hash::make($datos['contrasenia']),
                'edad' => $datos['edad'],
            ]);

            Caja::where('id_usuario', null)
                ->whereHas('envio', function ($q) use ($datos) {
                    $q->where('correo', $datos['correo']);
                })
                ->update(['id_usuario' => $usuario->id]);

            Envio::where('correo', $datos['correo'])
                ->whereNull('id_usuario')
                ->update(['id_usuario' => $usuario->id]);

            DB::commit();

            $token = $this->jwt->crearToken([
                'sub' => $usuario->id
            ]);

            return response()->json([
                'token' => $token,
                'mensaje' => config('messages.usuario.registro_exitoso')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'mensaje' => 'Error en el registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enviarCorreo(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string',
            'ocupacion' => 'required|string',
            'correo' => 'required|email',
            'edad' => 'required|integer|min:18|max:100',
            'tipoCaja' => 'required|string|exists:tipo_caja,id',
            'frecuenciaCaja' => 'required|string|exists:frecuencia_caja,id',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|integer',
            'productos.*.title' => 'required|string',
            'productos.*.price' => 'required|numeric',
            'productos.*.category' => 'required|string',
            'productos.*.image' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($datos) {

                $usuario = Usuario::where('correo', $datos['correo'])->first();

                $envio = Envio::create([
                    'id_usuario' => $usuario?->id,
                    'id_frecuencia' => $datos['frecuenciaCaja'],
                    'nombre' => $datos['nombre'],
                    'correo' => $datos['correo'],
                    'edad' => $datos['edad'],
                    'ocupacion' => $datos['ocupacion'],
                ]);

                $numeroCaja = Caja::whereHas('envio', function ($query) use ($datos) {
                    $query->where('correo', $datos['correo']);
                })->count() + 1;

                $nombreCaja = "Caja de intereses " . str_pad($numeroCaja, 3, '0', STR_PAD_LEFT);

                $caja = Caja::create([
                    'id_usuario' => $usuario?->id,
                    'id_envio' => $envio->id,
                    'id_tipo_caja' => $datos['tipoCaja'],
                    'nombre' => $nombreCaja,
                    'fecha_creacion' => now()
                ]);

                $productosIds = [];
                foreach ($datos['productos'] as $producto) {
                    $categoria = Categoria::firstOrCreate(['nombre' => $producto['category']]);

                    $productoModel = Producto::firstOrCreate(
                        ['api_id' => $producto['id']],
                        [
                            'nombre' => $producto['title'],
                            'precio' => $producto['price'],
                            'id_categoria' => $categoria->id
                        ]
                    );

                    $productosIds[] = $productoModel->id;
                }

                $caja->productos()->attach($productosIds);
            }, 5);

            DB::afterCommit(function () use ($datos) {
                Mail::to($datos['correo'])->send(new EnvioProductosMail([
                    'nombre' => $datos['nombre'],
                    'productos' => $datos['productos'],
                    'tipoCaja' => $datos['tipoCaja'],
                    'frecuenciaCaja' => $datos['frecuenciaCaja'],
                ]));
            });

            return response()->json([
                'mensaje' => config('messages.envio.exitoso')
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Error al enviar correo: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'mensaje' => 'Ocurrió un error al procesar el envío',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function iniciarSesion(Request $request)
    {
        $datos = $request->validate([
            'correo' => 'required|email',
            'contrasenia' => 'required'
        ]);

        $usuario = Usuario::where('correo', $datos['correo'])->first();

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'El correo no está registrado'
            ], 422);
        }

        if (!Hash::check($datos['contrasenia'], $usuario->contrasenia)) {
            return response()->json([
                'mensaje' => 'Credenciales inválidas'
            ], 422);
        }

        $token = $this->jwt->crearToken([
            'sub' => $usuario->id,
            'correo' => $usuario->correo,
            'nombre' => $usuario->nombre
        ]);

        return response()->json([
            'token' => $token,
            'mensaje' => 'Inicio de sesión exitoso'
        ]);
    }


    public function consultarToken(Request $request)
    {
        return response()->json($request->jwt_user);
    }
}
