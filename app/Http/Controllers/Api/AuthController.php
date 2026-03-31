<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credenciales = $request->only('email', 'password');

        $token = auth('api')->attempt($credenciales);

        if (empty($token)) {
            return response()->json(['error' => 'Usuario o contraseña incorrectos'], 422);
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
}
