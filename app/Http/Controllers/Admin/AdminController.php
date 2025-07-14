<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $users = \App\Models\Utilisateur::query();
        $articlesCount = \App\Models\Article::count();
        $categoriesCount = \App\Models\Categorie::count();
        
        return view('admin.dashboard', [
            'users' => $users->paginate(10),
            'articlesCount' => $articlesCount,
            'categoriesCount' => $categoriesCount,
        ]);
    }

    /**
     * Affiche la liste des utilisateurs.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = Utilisateur::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create', [
            'roles' => [
                'admin' => 'Administrateur', 
                'editeur' => 'Éditeur'
            ]
        ]);
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur.
     * (Méthode de compatibilité avec les anciens liens)
     *
     * @return \Illuminate\View\View
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Enregistre un nouvel utilisateur dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,editeur',
        ]);

        Utilisateur::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Ancienne méthode de création d'utilisateur (conservée pour compatibilité).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUser(Request $request)
    {
        return $this->store($request);
    }

    /**
     * Affiche le formulaire de modification d'un utilisateur.
     *
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\View\View
     */
    public function edit(Utilisateur $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => [
                'admin' => 'Administrateur', 
                'editeur' => 'Éditeur'
            ]
        ]);
    }
    
    /**
     * Ancienne méthode d'édition (conservée pour compatibilité).
     *
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\View\View
     */
    public function editUser(Utilisateur $user)
    {
        return $this->edit($user);
    }

    /**
     * Met à jour un utilisateur dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Utilisateur $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,editeur',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur mis à jour avec succès');
    }
    
    /**
     * Ancienne méthode de mise à jour (conservée pour compatibilité).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, Utilisateur $user)
    {
        return $this->update($request, $user);
    }

    /**
     * Supprime un utilisateur de la base de données.
     *
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Utilisateur $user)
    {
        // Empêche la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur supprimé avec succès');
    }
    
    /**
     * Ancienne méthode de suppression (conservée pour compatibilité).
     *
     * @param  \App\Models\Utilisateur  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser(Utilisateur $user)
    {
        return $this->destroy($user);
    }

    /**
     * Génère un nouveau jeton d'API pour l'utilisateur connecté
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateToken(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'sometimes|array',
            'abilities.*' => 'string',
        ]);

        $token = $request->user()->createToken(
            $request->name,
            $request->abilities ?? ['*']
        );

        return response()->json([
            'success' => true,
            'token' => $token->plainTextToken,
            'message' => 'Jeton généré avec succès. Copiez-le maintenant, vous ne pourrez plus le voir plus tard.',
        ]);
    }
    
    /**
     * Révoque le jeton d'API spécifié
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $tokenId
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeToken(Request $request, $tokenId = null)
    {
        if ($tokenId) {
            // Révoquer un jeton spécifique
            $request->user()->tokens()->where('id', $tokenId)->delete();
        } else {
            // Révoquer le jeton actuel
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Jeton révoqué avec succès',
        ]);
    }

    /**
     * Ancienne méthode de génération de jeton (conservée pour compatibilité)
     * 
     * @deprecated Utiliser generateToken() à la place
     * @return \Illuminate\Http\RedirectResponse
     */
    public function oldGenerateToken()
    {
        $token = Str::random(60);
        $user = auth()->user();
        
        // Créer un nouveau jeton avec Sanctum
        $tokenResult = $user->createToken('legacy-token');
        
        return back()->with([
            'token' => $tokenResult->plainTextToken,
            'success' => 'Nouveau jeton généré avec succès. Copiez-le maintenant, il ne sera plus affiché.'
        ]);
    }
    
    /**
     * Ancienne méthode de révocation de jeton (conservée pour compatibilité)
     * 
     * @deprecated Utiliser revokeToken() avec l'ID du jeton à la place
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revokeLegacyToken()
    {
        $user = auth()->user();
        
        // Supprimer tous les jetons de l'utilisateur
        $user->tokens()->delete();
        
        return back()->with('success', 'Tous les jetons ont été révoqués avec succès');
    }
}
