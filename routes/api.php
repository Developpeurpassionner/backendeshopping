<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MontresHommesController;
use App\Http\Controllers\MontresFommesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/inscription', [AuthController::class, 'inscription']); //route pour l'inscription
Route::post('/connexion', [AuthController::class, 'connexion']);    //route pour la connexion
Route::post('/montres_pour_hommes', [MontresHommesController::class, 'CreateMontreHommes']); //route pour la création d'une montre pour homme
Route::put('/montres_pour_hommes/{id}', [MontresHommesController::class, ' UpdateMontreHomme']); //route pour la modification d'une montre pour homme
Route::delete('/montres_pour_hommes/{id}', [MontresHommesController::class, 'destructionMontreHomme']); //route pour la suppression d'une montre pour homme
Route::post('/montres_pour_femmes', [MontresFemmesController::class, 'CreateMontreFemme']); //route pour la création d'une montre pour femme
Route::put('/montres_pour_femmes/{id}', [MontresFemmesController::class, ' UpdateMontreFemme']); //route pour la modification d'une montre pour femme
Route::delete('/montres_pour_femmes/{id}', [MontresFemmesController::class, 'destructionMontreFemme']); //route pour la suppression d'une montre pour femme
Route::post('/Yibshopp_Dashboard', [AuthDashboad::class, 'ConnexionDashboard']); //route pour la connexion au dashboard

