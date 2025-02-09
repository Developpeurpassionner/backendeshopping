<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RedirectIfNotRegistered;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/inscription', [AuthController::class, 'inscription']); //route pour l'inscription
Route::post('/connexion', [AuthController::class, 'connexion'])->middleware('redirect.if.not.registered');     //route pour la connexion
Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider']); //route pour l'inscription avec les réseaux sociaux
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']); //route pour la connexion avec les réseaux sociaux

