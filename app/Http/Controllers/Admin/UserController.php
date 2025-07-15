<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        $users = User::withCount('articles')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur
     */
    public function create()
    {
        $roles = [
            'admin' => 'Administrateur',
            'editeur' => 'Éditeur',
            'visiteur' => 'Visiteur'
        ];

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Enregistre un nouvel utilisateur
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in(['admin', 'editeur', 'visiteur'])],
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Générer un token API pour l'utilisateur
        $validated['api_token'] = Str::random(80);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès!');
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur
     *
     * @param \App\Models\User $user
     */
    public function edit(User $user)
    {
        $roles = [
            'admin' => 'Administrateur',
            'editeur' => 'Éditeur',
            'visiteur' => 'Visiteur'
        ];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Met à jour un utilisateur existant
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('utilisateurs', 'email')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in(['admin', 'editeur', 'visiteur'])],
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès!');
    }

    /**
     * Supprime un utilisateur
     *
     * @param \App\Models\User $user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte!');
        }

        if ($user->articles()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer cet utilisateur car il a des articles associés.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès!');
    }

    /**
     * Génère un nouveau token API pour un utilisateur
     *
     * @param \App\Models\User $user
     */
    public function generateApiToken(User $user)
    {
        $user->update(['api_token' => Str::random(80)]);

        return back()->with('success', 'Nouveau token API généré avec succès!');
    }
}
