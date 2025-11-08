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

        $fileName =$request->file('photo')->getClientOriginalName();
        $filePath = $request->file('photo')->storeAs('public/images_montres_femmes', $fileName);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tous les champs sont obligatoires et doivent respecter les conditions.',
                'error' => $validator->errors()
            ], 422);
        }
        // Extraire le premier mot du nom
        $categorie = explode(' ', $request->nom)[0];
        $montres_femmes = Montres_Femmes::create([
            'nom' => $request->nom,
            'photo'=>$request->photo = '/storage/images_montres_femmes/' . $fileName, // Chemin accessible
            'prix' => $request->prix,
            'description' => $request->description,
            'quantité' => $request->quantité,
            'genre' => 'femme', // Valeur par défaut
            'categorie' => $categorie,
        ]);
        return response()->json(['message' => 'Montre créé avec succès','MontreAdd'=> $montres_femmes], 201);
    }
}
