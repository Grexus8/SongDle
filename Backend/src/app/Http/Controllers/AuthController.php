<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function createUser(UserRequest $request)
    {
        try {
            // Creación del usuario (ahora podemos incluir la imagen vacía por defecto aquí directamente si queremos, o dejarla null)
            $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'registration_date' => $request->registration_date,
                    'password' => Hash::make($request->password),
                    'profile_img' => null, // Lo guardamos directamente en la tabla users
                ]);
            
            // Creamos el token a partir del usuario
            $token = $user->createToken('api-token')->plainTextToken;

            // Insertamos el token en las cookies
            $cookie = cookie('auth_token', $token, 9999, '/', null, false, true);

            // Respuesta en json (ya no usamos $user->load('profile'))
            return response()->json([
                'status' => 'true',
                'message' => 'Usuari creat correctament',
                'token' => $token,
                'user' => $user, 
            ], 200)->withCookie($cookie);

        } catch(Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => 'Error al crear el usuari',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function loginUser(LoginUserRequest $request)
    {
        try {
            // 1. Buscamos al usuario directamente en la base de datos por su nombre
            $user = User::where('name', $request->name)->first();

            // 2. Comprobamos si el usuario NO existe o si la contraseña es incorrecta
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'Credencials incorrectes',
                ], 401);
            }

            // 3. Si pasamos el filtro de arriba, todo está bien. Creamos el token.
            $token = $user->createToken('api-token')->plainTextToken;

            // Insertamos el token en las cookies
            $cookie = cookie('auth_token', $token, 9999, '/', null, false, true);

            // Respuesta en json
            return response()->json([
                'status' => 'true',
                'message' => 'Usuari autenticat correctament',
                'token' => $token,
                'user' => $user,
            ], 200)->withCookie($cookie);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => 'Error al autenticar el usuari',
                'error' => $e->getMessage(),
            ], 500);
        }  
    }
}