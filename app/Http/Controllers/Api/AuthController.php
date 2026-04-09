<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

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
                'refresh_token',
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


    public function registrar(Request $request)
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
            'refresh_token',
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
}
