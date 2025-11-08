<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Récupérer tous les utilisateurs
    public function RecupererUtilisateurs(Request $request): JsonResponse
    {
        $search = $request->query('search');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q
                    ->where('name', 'like', "{$search}%")
                    ->orWhere('firstname', 'like', "{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
}
