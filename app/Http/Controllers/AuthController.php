<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Inscription
    public function inscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',  // Au moins une majuscule
                'regex:/[a-z]/',  // Au moins une minuscule
                'regex:/[0-9]/',  // Au moins un chiffre ,
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tous les champs sont obligatoires et doivent respecter les conditions.',
                'error' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'firstname' => $request->firstname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Inscription réussie. Veuillez vous connectez pour effectuer des actions'], 201);
    }

    // Connexion
    public function connexion(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            // Rediriger vers le compte utilisateur avec un message de bienvenue
            return response()->json([
                'message' => 'Bienvenue ' . $user->firstname,
                'token' => $token,
                'user' => $user
            ], 200);
        } else {
            // Rediriger vers la page d'inscription
            return response()->json([
                'error' => 'Utilisateur inexistant',
                'redirect' => url('/inscription')
            ], 401);
        }
    }
}
