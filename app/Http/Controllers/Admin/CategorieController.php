<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::withCount('articles')
            ->latest()
            ->paginate(10);
            
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'couleur' => 'required|string|in:red,orange,yellow,green,blue,indigo,purple,pink,gray',
        ]);

        $categorie = new Categorie($validated);
        $categorie->slug = Str::slug($validated['nom']);
        $categorie->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie)
    {
        $categorie->loadCount('articles');
        $articles = $categorie->articles()
            ->with('user')
            ->latest()
            ->paginate(10);
            
        return view('admin.categories.show', compact('categorie', 'articles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $categorie)
    {
        return view('admin.categories.edit', compact('categorie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($categorie->id),
            ],
            'description' => 'nullable|string',
            'couleur' => 'required|string|in:red,orange,yellow,green,blue,indigo,purple,pink,gray',
        ]);

        $categorie->update($validated);
        $categorie->slug = Str::slug($validated['nom']);
        $categorie->save();

        return redirect()->route('admin.categories.show', $categorie)
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie)
    {
        // Vérifier si la catégorie est utilisée par des articles
        if ($categorie->articles()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette catégorie car elle est utilisée par des articles.');
        }
        
        $categorie->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
