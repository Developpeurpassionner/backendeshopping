<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MontresHommesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/inscription', [AuthController::class, 'inscription']); //route pour l'inscription
Route::post('/connexion', [AuthController::class, 'connexion']);    //route pour la connexion
Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider']); //route pour l'inscription avec les réseaux sociaux
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']); //route pour la connexion avec les réseaux sociaux
Route::post('/montres_pour_hommes', [MontresHommesController::class, 'CreateMontreHommes']); //route pour la création d'une montre pour homme
Route::put('/montres_pour_hommes/{id}', [MontresHommesController::class, ' UpdateMontreHomme']); //route pour la modification d'une montre pour homme
Route::delete('/montres_pour_hommes/{id}', [MontresHommesController::class, 'destructionMontreHomme']); //route pour la suppression d'une montre pour homme
Route::post('/montres_pour_femmes', [MontresFemmesController::class, 'CreateMontreFemme']); //route pour la création d'une montre pour femme
Route::put('/montres_pour_femmes/{id}', [MontresFemmesController::class, ' UpdateMontreFemme']); //route pour la modification d'une montre pour femme
Route::delete('/montres_pour_femmes/{id}', [MontresFemmesController::class, 'destructionMontreFemme']); //route pour la suppression d'une montre pour femme
