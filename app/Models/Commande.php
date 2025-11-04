<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'montre_id',
        'nom_client',
        'prenom_client',
        'quartier_client',
        'telephone_client',
        'photo_montre',
        'nom_montre',
        'genre_montre',
        'description_montre',
        'prix_unitaire_montre',
        'quantite_montre',
        'prix_total_montre',
    ];

    public function MontreHomme()
    {
        return $this->belongsTo(Montres_Hommes::class, 'montre_id');
    }

    public function MontreFemme()
    {
        return $this->belongsTo(Montres_Femmes::class, 'montre_id');
    }
}
