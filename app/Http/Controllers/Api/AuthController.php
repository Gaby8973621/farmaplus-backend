<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    ///////////// REGISTRO //////////////////
    public function register(Request $request)
    {
        $response = ["success" => false];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $user->assignRole('client');

        return response()->json([
            'success' => true,
            'token' => $user->createToken("farmacia")->plainTextToken,
            'user' => $user
        ], 201);
    }

    ///////////// LOGIN //////////////////
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if (!$user->hasRole('client')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado, el usuario no tiene el rol correcto.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'token' => $user->createToken("farmacia.app")->plainTextToken,
                'user' => $user
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    ///////////// LOGOUT //////////////////
    public function logout()
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada correctamente'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudo cerrar sesión'
        ], 400);
    }
}
