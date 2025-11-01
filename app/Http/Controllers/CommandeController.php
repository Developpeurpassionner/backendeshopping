<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Montres_Femmes;
use App\Models\Montres_Hommes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    /**
     * Valide et enregistre une commande.
     *
     * Les règles :
     * - genre_montre prendra par défaut "homme" ou "femme" selon que la montre se trouve
     *   dans la table montres_hommes ou montres_femmes.
     * - montre_id doit exister dans l'une ou l'autre table.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function creerCommande(Request $request): JsonResponse
    {
        // Validation uniquement des champs remplis par le client
        $validator = Validator::make($request->all(), [
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'required|string|max:255',
            'quartier_client' => 'required|string|max:255',
            'telephone_client' => 'required|string|max:20',
            'quantité_montre' => 'required|integer|min:1',
            'montre_id' => 'required|integer',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'integer' => 'Le champ :attribute doit être un entier.',
            'min' => 'Le champ :attribute doit être au moins :min.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Veuillez remplir tous les champs obligatoires.',
                'errors' => $validator->errors()
            ], 422);
        }

        $montreId = (int) $request->input('montre_id');
        $montre = Montres_Hommes::find($montreId);
        $genre = 'homme';

        if (!$montre) {
            $montre = Montres_Femmes::find($montreId);
            if (!$montre) {
                return response()->json([
                    'message' => 'La montre sélectionnée est introuvable.',
                    'errors' => ['montre_id' => ['ID invalide ou montre non trouvée.']]
                ], 422);
            }
            $genre = 'femme';
        }

        // Calcul du prix total
        $quantite = (int) $request->input('quantite_montre');
        $prixUnitaire = (float) $montre->prix;
        $prixTotal = $prixUnitaire * $quantite;

        // Enregistrement de la commande
        $commande = Commande::create([
            'nom_client' => $request->input('nom_client'),
            'prenom_client' => $request->input('prenom_client'),
            'quartier_client' => $request->input('quartier_client'),
            'telephone_client' => $request->input('telephone_client'),
            'photo_montre' => $montre->photo,
            'nom_montre' => $montre->nom,
            'genre_montre' => $genre,
            'description_montre' => $montre->description,
            'prix_unitaire_montre' => $prixUnitaire,
            'quantité_montre' => $quantite,
            'prix_total_montre' => $prixTotal,
            'montre_id' => $montreId,
        ]);

        return response()->json([
            'message' => 'Votre commande a bien été enregistrée.',
            'commande' => $commande,
        ], 201);
    }
}
