<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Affiche la liste des catégories
     */
    public function index()
    {
        $categories = Categorie::orderBy('nom')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Affiche le formulaire de création d'une catégorie
     */
    public function create()
    {
        return view('admin.categories.create');
    }
    
    /**
     * Enregistre une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
            'description' => 'nullable|string',
        ]);
        
        $validated['slug'] = Str::slug($validated['nom']);
        
        Categorie::create($validated);
        
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Affiche le formulaire d'édition d'une catégorie
     */
    public function edit(Categorie $categorie)
    {
        return view('admin.categories.edit', compact('categorie'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Met à jour une catégorie existante
     */
    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $categorie->id,
            'description' => 'nullable|string',
        ]);
        
        $validated['slug'] = Str::slug($validated['nom']);
        
        $categorie->update($validated);
        
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Supprime une catégorie
     */
    public function destroy(Categorie $categorie)
    {
        // Vérifier si la catégorie est utilisée par des articles
        if ($categorie->articles()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle est utilisée par un ou plusieurs articles.');
        }
        
        $categorie->delete();
        
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès!');
    }
}
