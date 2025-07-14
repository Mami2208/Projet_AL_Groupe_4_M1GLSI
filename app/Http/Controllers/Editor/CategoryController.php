<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = \App\Models\Categorie::orderBy('nom')
            ->paginate(10);
            
        return view('editor.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('editor.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:categories,nom',
            'description' => 'nullable|string|max:255',
            'couleur' => 'nullable|string|in:blue,red,green,yellow,indigo,purple,pink,gray',
        ]);

        // Création de la catégorie
        $categorie = new \App\Models\Categorie();
        $categorie->nom = $validated['nom'];
        $categorie->description = $validated['description'] ?? null;
        $categorie->couleur = $validated['couleur'] ?? 'blue';
        $categorie->save();

        return redirect()
            ->route('editor.categories.index')
            ->with('success', 'La catégorie a été créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
