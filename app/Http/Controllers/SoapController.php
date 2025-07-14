<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SoapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('soap.auth')->except(['authenticate']);
    }
    /**
     * Authentifier un utilisateur et obtenir un jeton d'API
     * 
     * @param string $email
     * @param string $password
     * @return array
     */
    public function authenticate($email, $password)
    {
        \Log::info("Tentative d'authentification pour: $email");
        
        $user = Utilisateur::where('email', $email)->first();

        if (!$user) {
            \Log::warning("Utilisateur non trouvé: $email");
            return [
                'success' => false,
                'message' => 'Identifiants invalides'
            ];
        }
        
        if (!Hash::check($password, $user->password)) {
            \Log::warning("Mot de passe incorrect pour: $email");
            return [
                'success' => false,
                'message' => 'Identifiants invalides'
            ];
        }

        $token = $user->createToken('soap-token')->plainTextToken;
        \Log::info("Authentification réussie pour: $email, token: $token");

        return [
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]
        ];
    }

    /**
     * Récupère la liste de tous les utilisateurs (admin uniquement)
     * 
     * @param string|null $token
     * @return array
     */
    public function listUsers($token = null)
    {
        $user = auth()->user();
        
        if ($user->role !== 'admin') {
            return [
                'success' => false,
                'message' => 'Accès non autorisé. Rôle administrateur requis.'
            ];
        }

        $users = Utilisateur::all(['id', 'name', 'email', 'role', 'created_at', 'updated_at']);
        
        return [
            'success' => true,
            'users' => $users->toArray()
        ];
    }

    /**
     * Ajouter un nouvel utilisateur (admin uniquement)
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $role
     * @return array
     */
    public function addUser($name, $email, $password, $role = 'visiteur')
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return [
                'success' => false,
                'message' => 'Accès non autorisé. Rôle administrateur requis.'
            ];
        }

        if (Utilisateur::where('email', $email)->exists()) {
            return [
                'success' => false,
                'message' => 'Cet email est déjà utilisé'
            ];
        }

        $newUser = Utilisateur::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => in_array($role, ['visiteur', 'editeur', 'admin']) ? $role : 'visiteur',
        ]);

        return [
            'success' => true,
            'message' => 'Utilisateur créé avec succès',
            'user_id' => $newUser->id
        ];
    }

    /**
     * Mettre à jour un utilisateur (admin uniquement)
     *
     * @param int $userId
     * @param array $data
     * @return array
     */
    public function updateUser($userId, $data)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return [
                'success' => false,
                'message' => 'Accès non autorisé. Rôle administrateur requis.'
            ];
        }

        $targetUser = Utilisateur::find($userId);
        if (!$targetUser) {
            return [
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ];
        }

        // Nettoyer les données pour ne mettre à jour que les champs autorisés
        $allowedFields = ['name', 'email', 'password', 'role'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));
        
        // Hasher le mot de passe s'il est fourni
        if (isset($updateData['password'])) {
            $updateData['password'] = Hash::make($updateData['password']);
        }

        $targetUser->update($updateData);

        return [
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès',
            'user' => $targetUser->only(['id', 'name', 'email', 'role'])
        ];
    }

    /**
     * Supprimer un utilisateur (admin uniquement)
     *
     * @param int $userId
     * @return array
     */
    public function deleteUser($userId)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return [
                'success' => false,
                'message' => 'Accès non autorisé. Rôle administrateur requis.'
            ];
        }

        if ($user->id == $userId) {
            return [
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte'
            ];
        }

        $targetUser = Utilisateur::find($userId);
        if (!$targetUser) {
            return [
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ];
        }

        $targetUser->delete();

        return [
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès'
        ];
    }

    /**
     * Vérifier la validité d'un token
     * 
     * @param string $token
     * @return User|null
     */
    private function verifyToken($token)
    {
        $user = \App\Models\Utilisateur::whereHas('tokens', function ($query) use ($token) {
            $query->where('token', hash('sha256', $token));
        })->first();

        return $user;
    }
}
