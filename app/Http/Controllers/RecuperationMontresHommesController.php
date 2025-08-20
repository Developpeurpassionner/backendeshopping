<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RecuperationMontresHommesController extends Controller
{
    // fonction pour récupérer toutes les montres pour hommes
    public function GetMontresHommes(){
        $montreshommes = DB::table('montres__hommes')->get();
        // Réponse JSON pour le frontend
        return response()->json([
            'success' => true,
            'data' => $montreshommes
        ]);
    }
}
