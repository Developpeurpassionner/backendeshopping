<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    public function AjouterAuPanier(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Veuillez vous connecter d\'abord.'], 401);
        }

        // Votre logique pour ajouter un produit au panier
        
        
        return response()->json(['message' => 'Produit ajouté au panier avec succès.']);
    }
}
