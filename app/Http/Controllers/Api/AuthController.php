<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller

{
    //////////////// metodo del regitro ///////////////
    public function register(Request $request){

        $response = ["success"=>false];

        //esto es para agregar el usuario y se le asigne el rol
        //validacion de los datos
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            $response = ["error"=>$validator->errors()];
            return response()->json($response, 200);
        }

        $input = $request->all();
        // contra incriptada de laravel
        $input["password"] = bcrypt($input['password']);


        //para crear el usuario
        $user = User::create($input);
        //asignarle el rol
        $user->assignRole('client');

        $response["success"] = true;
        $response["token"] = $user->createToken("farmacia")->plainTextToken;

        return response()->json($response, 200);
    }

    //////////////// metodo del login ////////////////

    public function login(Request $request){
        $response = ["success"=>false];

        //esto es para agregar el usuario y se le asigne el rol
        //validacion de los datos
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            $response = ["error"=>$validator->errors()];
            return response()->json($response, 200);
        }

        ///autentificacion///
        if(auth()->attempt(['email' => $request->email, 'password' => $request->password])){
            $user = auth()->user();
            $user->hasRole('client'); //para saber que rol tiene el usuario

            $response['token'] = $user->createToken("farmacia.app")->plainTextToken;
            $response['user'] = $user;
            $response['success'] = true;
        };
        return response()->json($response, 200);

    }

    /////////////// metodo del logout ////////////////

    public function logout(){
        $response=["success"=>false];
        // elimina todos los tokens
        auth()->user()->tokens()->delete();
        $response=[
            "success"=>true,
            "message"=>"Sesion cerrada"
        ];
        return response()->json($response, 200);

    }


}
