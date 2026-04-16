<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Http\Requests\RegistroRequest;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credenciales = $request->only('email', 'password');

        $token = auth('api')->attempt($credenciales);

        if (empty($token)) {
            return response()->json(['errors' => 'Usuario o contraseña incorrectos'], 422);
        }

        $user = auth('api')->user();
        $user->load('rol');


        $ttlOriginal = JWTAuth::factory()->getTTL();
        JWTAuth::factory()->setTTL(120);
        $refreshToken  =  JWTAuth::claims(['type' => 'refresh'])->fromUser($user);
        JWTAuth::factory()->setTTL($ttlOriginal);

        unset($user->password, $user->id);

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ])
            ->cookie(
                'refreshToken',
                $refreshToken,
                120, // expiracion de la cookie en minutos,
                '/', // path
                null,
                false, // secure (cambiar en produccion a true)
                true, // httpOnly
                false,
                'Lax',
            );
    }


    public function registrar(RegistroRequest $request)
    {

        $request->merge(['rol_id' => 3]);




        $usuario = User::create($request->all());

        if (!$usuario) {
            return response()->json([
                'errors' => 'Error al registrar el usuario'
            ], 500);
        }

        $token  =  JWTAuth::fromUser($usuario);
        $usuario->load('rol');

        $ttlOriginal = JWTAuth::factory()->getTTL();
        JWTAuth::factory()->setTTL(120);
        $refreshToken  =  JWTAuth::claims(['type' => 'refresh'])->fromUser($usuario);
        JWTAuth::factory()->setTTL($ttlOriginal);

        unset($usuario->password, $usuario->id);


        return response()->json([
            'access_token' => $token,
            'user' => $usuario,
        ])->cookie(
            'refreshToken',
            $refreshToken,
            120, // expiracion de la cookie en minutos,
            '/', // path
            'localhost', // dominio
            false, // secure (cambiar en produccion a true)
            true, // httpOnly
            false,
            'Lax',
        );
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refreshToken');
        if (!$refreshToken) {
            return response()->json([
                'errors' => 'No se proporcionó un token de actualización'
            ], 401);
        }


        try {

            $payload = JWTAuth::setToken($refreshToken)->getPayload();

            if ($payload->get('type') !== 'refresh') {
                return response()->json([
                    'errors' => 'No se proporcionó un token de actualización'
                ], 401);
            }

            $user = User::findOrFail($payload->get('sub'));
            $newToken = JWTAuth::fromUser($user);
            return response()->json([
                'access_token' => $newToken,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                // 'errors' => $e->getMessage()
                'errors' => 'Token de actualización inválido o expirado'
            ], 401)->cookie('refreshToken', null, -1);
        }
    }


    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ])->cookie('refreshToken', null, -1);
    }
}
