<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Montres_Femmes;
class RecupererMontresFemmesController extends Controller
{
    // fonction pour récupérer toutes les montres femmes
    public function getmontresfemmes()
    {
        return Montres_Femmes::all();
    }
}
