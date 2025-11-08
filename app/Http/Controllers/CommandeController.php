<?php
namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Montres_Femmes;
use App\Models\Montres_Hommes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

set_time_limit(60);

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
    public function créercommande(Request $request): JsonResponse
    {
        // Validation uniquement des champs remplis par le client
        $validator = Validator::make($request->all(), [
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'required|string|max:255',
            'quartier_client' => 'required|string|max:255',
            'telephone_client' => 'required|regex:/^[0-9]+$/',
            'quantite_montre' => 'required|integer|min:1',
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
        $genre = $request->input('genre_montre');

        if ($genre === 'homme') {
            $montre = Montres_Hommes::find($montreId);
        } elseif ($genre === 'femme') {
            $montre = Montres_Femmes::find($montreId);
        } else {
            return response()->json([
                'message' => 'Genre invalide ou non spécifié.',
                'errors' => ['genre_montre' => ['Le genre doit être "homme" ou "femme".']]
            ], 422);
        }

        if (!$montre) {
            return response()->json([
                'message' => 'La montre sélectionnée est introuvable.',
                'errors' => ['montre_id' => ['ID invalide ou montre non trouvée.']]
            ], 422);
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
            'quantite_montre' => is_numeric($request->input('quantite_montre'))
                ? (int) $request->input('quantite_montre')
                : 1,
            'prix_total_montre' => $prixTotal,
            'montre_id' => $montreId,
        ]);

        return response()->json([
            'message' => 'Votre commande a bien été enregistrée.',
            'commande' => $commande,
        ], 201);
    }

    public function reinitialiserAutoIncrement()
    {
        $nextId = DB::table('commandes as t1')
            ->leftJoin('commandes as t2', DB::raw('t1.id + 1'), '=', 't2.id')
            ->whereNull('t2.id')
            ->min(DB::raw('t1.id + 1'));

        $nextId = $nextId ?? 1;

        DB::statement("ALTER TABLE commandes AUTO_INCREMENT = {$nextId}");

        return response()->json([
            'message' => "AUTO_INCREMENT réinitialisé à {$nextId}"
        ]);
    }

    public function RecupererToutesLesCommandes(Request $request)
    {
        $genre = $request->query('genre');  // homme ou femme
        $query = Commande::query();

        if ($genre) {
            $query->where('genre_montre', $genre);
        }

        $commandes = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($commandes);
    }

    public function chiffreAffaireTotal()
    {
        $total = DB::table('commandes')->sum('prix_total_montre');

        return response()->json([
            'success' => true,
            'chiffre_d_affaire_total' => $total
        ]);
    }
}
