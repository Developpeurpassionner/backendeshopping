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
        'description',
    ];
}
