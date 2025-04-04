<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Montres_Hommes extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'photo',
        'prix',
        'genre',
        'description',
        'quantité',
    ];

    protected static function booted()
    {
        static::updating(function ($model) {
            if ($model->isDirty('genre')) {
                throw new \Exception('Le champ "genre" ne peut pas être modifié.');
            }
        });
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}
