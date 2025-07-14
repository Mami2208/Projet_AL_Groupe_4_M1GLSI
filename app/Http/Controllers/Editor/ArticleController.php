<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer les articles de l'utilisateur connecté, triés par date de création décroissante
        $articles = Article::where('utilisateur_id', auth()->id())
                         ->with('categorie')
                         ->latest()
                         ->paginate(10);
        
        return view('editor.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Récupérer les catégories avec seulement les champs nécessaires
        $categories = Categorie::select('id', 'nom', 'couleur')
                             ->orderBy('nom')
                             ->get()
                             ->mapWithKeys(function ($categorie) {
                                 return [
                                     $categorie->id => [
                                         'nom' => $categorie->nom,
                                         'couleur' => $categorie->couleur
                                     ]
                                 ];
                             })
                             ->toArray();
        
        return view('editor.articles.create', compact('categories'));
    }

    /**
     * Store a newly created article in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Génération automatique du slug à partir du titre
            $request->merge([
                'slug' => Str::slug($request->titre)
            ]);

            // Validation des données
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:articles,slug',
                'resume' => 'nullable|string|max:500',
                'contenu' => 'required|string|min:100',
                'categorie_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ], [
                'contenu.min' => 'Le contenu doit faire au moins 100 caractères.',
                'slug.unique' => 'Un article avec un titre similaire existe déjà. Veuillez modifier le titre.',
                'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
                'image.mimes' => 'Le format de l\'image doit être jpeg, png, jpg, gif ou svg.'
            ]);

            // Création du dossier de stockage s'il n'existe pas
            if ($request->hasFile('image') && !is_dir(storage_path('app/public/articles'))) {
                mkdir(storage_path('app/public/articles'), 0755, true);
            }

            // Traitement de l'image si elle est fournie
            $imagePath = null;
            if ($request->hasFile('image')) {
                try {
                    $imagePath = $request->file('image')->store('articles', 'public');
                } catch (\Exception $e) {
                    return back()->withInput()->withErrors([
                        'image' => 'Erreur lors du téléchargement de l\'image : ' . $e->getMessage()
                    ]);
                }
            }

            // Création de l'article
            $article = new Article();
            $article->titre = $validated['titre'];
            $article->slug = $validated['slug'];
            $article->resume = $validated['resume'] ?? null;
            $article->contenu = $validated['contenu'];
            $article->categorie_id = $validated['categorie_id'];
            $article->image = $imagePath;
            $article->utilisateur_id = Auth::id();
            $article->est_publie = false; // Par défaut, l'article n'est pas publié
            
            if ($article->save()) {
                return redirect()->route('editor.articles.edit', $article->id)
                    ->with('success', 'L\'article a été créé avec succès. Il est en attente de validation.');
            }

            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création de l\'article.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)
                         ->withInput()
                         ->with('error', 'Veuillez corriger les erreurs ci-dessous.');
        } catch (\Exception $e) {
            return back()->withInput()
                         ->with('error', 'Une erreur inattendue est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::findOrFail($id);
        return view('editor.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        $categories = Categorie::orderBy('nom')->pluck('nom', 'id');
        
        return view('editor.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Récupérer l'article
            $article = Article::findOrFail($id);
            
            // Valider les données
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:articles,slug,' . $article->id,
                'resume' => 'nullable|string|max:500',
                'contenu' => 'required|string|min:100',
                'categorie_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'remove_image' => 'nullable|boolean'
            ], [
                'contenu.min' => 'Le contenu doit faire au moins 100 caractères.',
                'slug.unique' => 'Un article avec ce slug existe déjà. Veuillez modifier le titre.',
                'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
                'image.mimes' => 'Le format de l\'image doit être jpeg, png, jpg, gif ou svg.'
            ]);
            
            // Gestion de l'image
            if ($request->has('remove_image') && $article->image) {
                // Supprimer l'image existante
                Storage::delete('public/' . $article->image);
                $article->image = null;
            } elseif ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($article->image) {
                    Storage::delete('public/' . $article->image);
                }
                
                // Stocker la nouvelle image
                $imagePath = $request->file('image')->store('articles', 'public');
                $validated['image'] = $imagePath;
            }
            
            // Mise à jour de l'article
            $article->update($validated);
            
            // Redirection avec message de succès
            return redirect()->route('editor.articles.edit', $article->id)
                ->with('success', 'L\'article a été mis à jour avec succès.');
                
        } catch (\Exception $e) {
            // En cas d'erreur, on revient en arrière avec les erreurs
            return back()->withInput()
                         ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'article : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
