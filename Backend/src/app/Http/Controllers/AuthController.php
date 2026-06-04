<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function createUser(UserRequest $request)
    {
        try {
            $rutaFoto = null;
            
            if ($request->hasFile('profile_img')) {
                $rutaFoto = $request->file('profile_img')->store('perfiles', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'registration_date' => $request->registration_date,
                'password' => Hash::make($request->password),
                'profile_img' => $rutaFoto, 
            ]);
            
            $token = $user->createToken('api-token')->plainTextToken;
            $cookie = cookie('auth_token', $token, 9999, '/', null, false, true);

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
            $user = User::where('name', $request->name)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'Credencials incorrectes',
                ], 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            $cookie = cookie('auth_token', $token, 9999, '/', null, false, true);

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

    public function logout(Request $request)
    {
        try {
            // Eliminamos el token actual
            $request->user()->currentAccessToken()->delete();
            
            // Borramos la cookie
            $cookie = cookie()->forget('auth_token');

            return response()->json([
                'status' => 'true',
                'message' => 'Sessió tancada correctament'
            ], 200)->withCookie($cookie);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => 'Error al tancar la sessió',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}