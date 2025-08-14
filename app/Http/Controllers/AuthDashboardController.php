<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthDashboardController extends Controller
{
    //connexion au Dashboard
    public function ConnexionDashboard(Request $request){
         // Validation des champs
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
         // Vérification manuelle
        if ($request->username === 'ibrahim' && $request->password === 'ibrahim1995') {
            return response()->json([
                'message' => 'Connexion réussie',
                'redirect' => '/dashboard'
            ], 200);
        }
          // Échec
        return response()->json([
            'message' => 'Les informations rentrées ne sont pas correctes.'
        ], 401);
    }
}
