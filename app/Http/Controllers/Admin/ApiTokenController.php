<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenController extends Controller
{
    /**
     * Afficher la liste des jetons d'API
     */
    public function index()
    {
        $tokens = auth()->user()->tokens()->latest()->get();
        return view('admin.api.tokens', compact('tokens'));
    }

    /**
     * Créer un nouveau jeton d'API
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'required|array',
            'abilities.*' => 'string|in:create,read,update,delete' // facultatif : limiter les permissions
        ]);

        $token = $request->user()->createToken(
            $request->input('name'),
            $request->input('abilities')
        );

        Log::info('Jeton API créé', [
            'user_id' => $request->user()->id,
            'token_id' => $token->accessToken->id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jeton créé avec succès.',
            'token' => $token->plainTextToken,
            'token_id' => $token->accessToken->id,
            'name' => $token->accessToken->name,
            'abilities' => $token->accessToken->abilities
        ], 201);
    }

    /**
     * Supprimer un jeton d'API
     */
    public function deleteToken($tokenId)
    {
        $token = PersonalAccessToken::find($tokenId);

        if (!$token) {
            return redirect()->route('admin.api.tokens')->with('status', [
                'type' => 'error',
                'message' => 'Jeton non trouvé.'
            ]);
        }

        if ($token->tokenable_id !== auth()->id()) {
            abort(403, 'Vous n’êtes pas autorisé à supprimer ce jeton.');
        }

        $token->delete();

        Log::warning('Jeton API supprimé', [
            'token_id' => $tokenId,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('admin.api.tokens')->with('status', [
            'type' => 'success',
            'message' => 'Le jeton a été supprimé avec succès.'
        ]);
    }

    /**
     * Récupérer la liste des jetons d'API pour l'utilisateur connecté
     */
    public function getTokens(Request $request)
    {
        $tokens = $request->user()->tokens;

        return response()->json([
            'tokens' => $tokens->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'last_used' => $token->last_used_at,
                    'created_at' => $token->created_at,
                    'abilities' => $token->abilities
                ];
            })
        ]);
    }
}
