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
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'prix' => 'required|integer',
            'description' => 'required|string',
            'quantité' => 'required|integer',
        ]);
        $fileName =$request->file('photo')->getClientOriginalName();
        $filePath = $request->file('photo')->storeAs('public/images_montres_hommes', $fileName);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tous les champs sont obligatoires et doivent respecter les conditions.',
                'error' => $validator->errors()
            ], 422);
        }
         // Extraire le premier mot du nom
        $categorie = explode(' ', $request->nom)[0];
        $montres_hommes = Montres_Hommes::create([
            'nom' => $request->nom,
            'photo'=>$request->photo = '/storage/images_montres_hommes/' . $fileName, // Chemin accessible
            'prix' => $request->prix,
            'description' => $request->description,
            'quantité' => $request->quantité,
            'genre' => 'homme', // Valeur par défaut
             'categorie' => $categorie,
        ]);
        return response()->json(['message' => 'Montre créé avec succès','MontreAdd'=> $montres_hommes], 201);
    }
}
