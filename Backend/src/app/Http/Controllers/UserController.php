<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function show($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404);
        }
        
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|string|email|unique:users,email,' . $id . ',id_usuario',
            'phone' => 'sometimes|string',
            'password' => 'sometimes|string|min:6',
            'administrador' => 'sometimes|boolean',
        ]);

        if (isset($validated['name'])) $user->name = $validated['name'];
        if (isset($validated['email'])) $user->email = $validated['email'];
        if (isset($validated['phone'])) $user->phone = $validated['phone'];
        if (isset($validated['administrador'])) $user->administrador = $validated['administrador'];
        
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Usuari actualitzat',
            'user' => $user
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuari eliminat correctament'], 200);
    }
}