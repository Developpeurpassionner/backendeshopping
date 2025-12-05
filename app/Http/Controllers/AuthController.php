<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Inscription
    public function inscription(Request $request)
    {
        // Vérifie si l'email existe déjà
        $existingUser = User::where('email', $request->email)->first();
        // Si l'utilisateur existe, renvoi le vers la page de connexion
        if ($existingUser) {
            return response()->json([
                'message' => 'Vous avez déja un compte. Veuillez vous connecter.',
                'redirect' => url('/connexion')
            ], 409);  // 409 = Conflict
        }
        //Les conditions de validation des champs
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
        //Renvoie un message d'erreur si la validation échoue
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tous les champs sont obligatoires et doivent être remplis correctement.',
                'error' => $validator->errors()
            ], 422);
        }
        // Crée l'utilisateur avec un message de succès en retour
        $user = User::create([
            'name' => $request->name,
            'firstname' => $request->firstname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Inscription réussie. Veuillez vous connectez pour effectuer des actions'], 201);
    }

    // Connexion avec OTP
    public function connexion(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        // Si l'utilisateur existe
        if ($user) {
            // Génère un code OTP à 6 chiffres
            $otp = rand(100000, 999999);

            // Sauvegarde le code et sa date d’expiration
            $user->otp_code = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);  // expire dans 10 minutes
            $user->save();

            // Envoie le code par email (tu peux aussi utiliser une API SMS)
            Mail::raw("Votre code est : $otp", function ($message) use ($user) {
                $message
                    ->to($user->email)
                    ->subject('Votre code de connexion');
            });

            return response()->json([
                'message' => 'Un code vous a été envoyé par email.', // Message de confirmation du code OTP envoyé
                'redirect' => url('/connexionOTP')  // Redirection vers le formulaire pour rentrer le code OTP
            ], 200);
            // Si l'utilisateur n'existe pas, renvoi un message d'erreur avec redirection vers l'inscription
        } else {
            return response()->json([
                'message' => "Vous n'êtes pas inscrit! Veuillez vous inscrire.",
                'error' => 'Utilisateur inexistant',
                'redirect' => url('/inscription')
            ], 404);
        }
    }
    // Vérification de l'OTP
    public function verifierOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|digits:6' // l'OTP doit être composé de 6 chiffres
        ]);

        $user = User::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('otp_expires_at', '>', now())
            ->first();
        // Si l'OTP est invalide/expiré
        if (!$user) {
            return response()->json(['message' => 'OTP invalide ou expiré.'], 401);
        }

        // Réinitialise l’OTP après une connexion réussie ou après expiration
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie.',
            'token' => $token,
            'user' => $user,
            'redirect' => url('/')
        ]);
    }
}
