<?php

namespace App\Http\Controllers;
use App\Models\Montres_Femmes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MontresFemmesController extends Controller
{
    //Création d'une montre pour femme
    public function CreateMontreFemme(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'prix' => 'required|integer',
            'description' => 'required|string',
            'quantité' => 'required|integer',
        ]);

        $fileName = time().'_'.$request->file('photo')->getClientOriginalName();
        $filePath = $request->file('photo')->storeAs('public/images_montres_femmes', $fileName);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tous les champs sont obligatoires et doivent respecter les conditions.',
                'error' => $validator->errors()
            ], 422);
        }

        $montres_femmes = Montres_Femmes::create([
            'nom' => $request->nom,
            'photo'=>$request->photo = '/storage/images_montres_femmes/' . $fileName, // Chemin accessible
            'prix' => $request->prix,
            'description' => $request->description,
            'quantité' => $request->quantité,
            'genre' => 'femme', // Valeur par défaut
        ]);
        return response()->json(['message' => 'Montre créé avec sucssès'], 201);
    }

    // Modification
    public function UpdateMontreFemme(Request $request, $id)
    {
        // Validation des données du formulaire
        $validator = $request->validate([
            'nom' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'prix' => 'required|integer',
            'description' => 'required|string',
            'quantité' => 'required|integer',
        ]);

        // Trouver la montre par son ID
        $montres_femmes = Montres_Femmes::findOrFail($id);

        // Mettre à jour la montre avec les nouvelles données
        $montres_femmes->update($validator);

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
    public function destructionMontreFemme($id)
    {
        // Trouver la montre par son ID
        $montres_femmes = Montres_Femmes::findOrFail($id);
        // Supprimer la montre
        $montres_femmes->delete();

        // Retourner une réponse JSON indiquant la suppression
        return response()->json(['message' => 'Montre supprimée avec sucssès'], 204);
    }
}
