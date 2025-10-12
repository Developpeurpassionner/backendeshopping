<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Montres_Hommes;
class RecupererMontresHommesController extends Controller
{
    // fonction pour récupérer toutes les montres
    public function getmontreshommes()
    {
         return response()->json(Montres_Hommes::all());
    }
}
