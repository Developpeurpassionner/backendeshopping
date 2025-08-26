<?php

namespace App\Http\Controllers;

use App\Models\Montres_Femmes;
use App\Models\Montres_Hommes;
use Illuminate\Http\Request;

class RecuperationMontresController extends Controller
{
    // fonction pour récupérer toutes les montres
    public function GetMontres()
    {
        $hommes = Montres_Hommes::all()->map(function ($item) {
            $item->genre = 'homme';
            return $item;
        });

        $femmes = Montres_Femmes::all()->map(function ($item) {
            $item->genre = 'femme';
            return $item;
        });

        $toutesLesMontres = $hommes->concat($femmes)->values();  // .values() pour réindexer

        return response()->json($toutesLesMontres);
    }
}
