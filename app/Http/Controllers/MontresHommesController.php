<?php

namespace App\Http\Controllers;
use App\Models\Montres_Hommes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MontresHommesController extends Controller
{
    //Création d'une montre pour homme
    public function CreateMontreHommes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'photo' => 'required|string|mimes:jpeg,png,jpg,gif,svg',
            'prix' => 'required|integer',
            'description' => 'required|text',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tous les champs sont obligatoires et doivent respecter les conditions.',
                'error' => $validator->errors()
            ], 422);
        }

        $montres_hommes = Montres_Hommes::create([
            'nom' => $request->name,
            'photo' => $request->photo,
            'prix' => $request->prix,
            'description' => $request->description,
        ]);
        return response()->json(['message' => 'Montre créé avec sucssès'], 201);
    }

    // Modification
    public function UpdateMontreHomme(Request $request, $id)
    {
        // Validation des données du formulaire
        $validator = $request->validate([
           'nom' => 'required|string|max:255',
            'photo' => 'required|string|mimes:jpeg,png,jpg,gif,svg',
            'prix' => 'required|integer',
            'description' => 'required|text',
        ]);

        // Trouver la montre par son ID
        $montres_hommes = Montres_Hommes::findOrFail($id);

        // Mettre à jour la montre avec les nouvelles données
        $montres_hommes->update($validator);

        // Retourner une réponse JSON avec la montre mise à jour
        return response()->json(['message' => 'Montre modifiée avec sucssès'], 200);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tous les champs sont obligatoires et doivent respecter les conditions.',
                'error' => $validator->errors()
            ], 422);
        }
    }
    // Suppression
    public function destructionMontreHomme($id)
    {
        // Trouver la montre par son ID
        $montres_hommes = Montres_Hommes::findOrFail($id);

        // Supprimer la montre
        $montres_hommes->delete();

        // Retourner une réponse JSON indiquant la suppression
        return response()->json(['message' => 'Montre supprimée avec sucssès'], 204);
    }
}
