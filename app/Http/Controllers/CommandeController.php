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
        $validator = Validator::make($request->all(), [
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'required|string|max:255',
            'quartier_client' => 'required|string|max:255',
            'telephone_client' => 'required|string|max:20',
            'photo_montre' => 'nullable|image|max:5120',
            'nom_montre' => 'required|string|max:255',
            'description_montre' => 'nullable|string',
            'genre_montre' => 'nullable|string|in:homme,femme',
            'prix_unitaire_montre' => 'required|numeric|min:0',
            'quantite_montre' => 'required|integer|min:1',
            'montre_id' => 'required|integer',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'image' => 'Le fichier :attribute doit être une image.',
            'in' => 'Le champ :attribute doit être "homme" ou "femme".',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'integer' => 'Le champ :attribute doit être un entier.',
            'min' => 'Le champ :attribute ne respecte pas la valeur minimale.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $montreId = (int) $request->input('montre_id');

        // Cherche la montre dans les deux tables
        $montre = MontreHomme::find($montreId);
        $genreDefaut = null;

        if ($montre === null) {
            $montre = MontreFemme::find($montreId);
            if ($montre === null) {
                return response()->json([
                    'errors' => ['montre_id' => ['La montre sélectionnée est introuvable dans nos catalogues.']]
                ], 422);
            }
            $genreDefaut = 'femme';
        } else {
            $genreDefaut = 'homme';
        }

        // Détermine le genre (valeur fournie ou valeur par défaut selon la table trouvée)
        $genre = $request->input('genre_montre') ?? $genreDefaut;

        // Gère l'upload de la photo si présente
        $photoPath = null;
        if ($request->hasFile('photo_montre')) {
            $photoPath = $request->file('photo_montre')->store('commandes', 'public');
        }

        $prixUnitaire = (float) $request->input('prix_unitaire_montre');
        $quantite = (int) $request->input('quantite_montre');
        $prixTotal = $prixUnitaire * $quantite;

        // Enregistrement de la commande
        $commande = Commande::create([
            'nom_client' => $request->input('nom_client'),
            'prenom_client' => $request->input('prenom_client'),
            'quartier_client' => $request->input('quartier_client'),
            'telephone_client' => $request->input('telephone_client'),
            'photo_montre' => $photoPath,
            'nom_montre' => $request->input('nom_montre'),
            'genre_montre' => $genre,
            'description_montre' => $request->input('description_montre'),
            'prix_unitaire_montre' => $prixUnitaire,
            'quantite_montre' => $quantite,
            'prix_total_montre' => $prixTotal,
            'montre_id' => $montreId,
        ]);

        return response()->json([
            'message' => 'Commande créée avec succès.',
            'commande' => $commande,
        ], 201);
    }
}
