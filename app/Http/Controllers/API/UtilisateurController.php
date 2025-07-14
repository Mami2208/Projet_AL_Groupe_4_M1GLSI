<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UtilisateurController extends Controller
{
    /**
     * Créer un nouveau jeton d'API pour l'utilisateur
     */
    private function createToken(Utilisateur $user)
    {
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|in:visiteur,editeur,admin'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Utilisateur::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'visiteur',
        ]);

        return $this->createToken($user);
    }

    /**
     * Authentifier un utilisateur
     */
    public function login(Request $request)
    {
        // Vérifier si la requête est de type JSON
        if ($request->isJson()) {
            $data = $request->json()->all();
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;
        } else {
            $email = $request->input('email');
            $password = $request->input('password');
        }

        $validator = Validator::make(['email' => $email, 'password' => $password], [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Utilisateur::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Identifiants invalides',
                'errors' => [
                    'email' => ['Les identifiants fournis sont incorrects.']
                ]
            ], 401);
        }

        return $this->createToken($user);
    }

    /**
     * Déconnexion (révoquer le token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /**
     * Obtenir le profil de l'utilisateur connecté
     */
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:utilisateurs,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['name', 'email']);
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return response()->json($user);
    }
}
