<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /// Para mostrar la lista de los usuarios ///
    public function index()
    {
        $data = User::get(["id", "name"]);
        return response()->json($data, 200);
    }

    /// Mostrar un usuario ///
    public function show($id)
    {
        $data = User::find($id);

        if (!$data) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($data, 200);
    }

    /// Actualizar un usuario ///
    public function update(Request $request, $id)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed', // password_confirmation debe venir
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Buscar usuario
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $input = $request->all();

        // Si hay contraseña la incriptamos con bcrypt//
        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        // Actualizar datos
        $user->fill($input);
        $user->save();

        return response()->json($user, 200);
    }

    /// para eliminar un usuario ///
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito'], 200);
    }
}
