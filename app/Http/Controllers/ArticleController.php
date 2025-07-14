<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Affiche la liste des articles pour l'édition
     */
    public function index()
    {
        $user = Auth::user();
        
        // Les administrateurs voient tous les articles
        // Les éditeurs ne voient que leurs articles
        $query = Article::with(['categorie', 'auteur'])
            ->when(!$user->isAdmin(), function($q) use ($user) {
                return $q->where('utilisateur_id', $user->id);
            })
            ->latest('date_publication');
            
        $articles = $query->paginate(10);
        
        // Si la requête vient du tableau de bord éditeur, on utilise la vue du dashboard
        if (request()->routeIs('editor.*')) {
            return view('editor.dashboard', [
                'articles' => $articles,
                'categories' => Categorie::all()
            ]);
        }
        
        return view('articles.index', compact('articles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Affiche le formulaire de création d'article
     */
    public function create()
    {
        $categories = Categorie::all();
        
        // Si la requête vient du tableau de bord éditeur, on utilise la vue du dashboard
        if (request()->routeIs('editor.*')) {
            return view('editor.articles.create', compact('categories'));
        }
        
        return view('articles.create', compact('categories'));
    }
    
    /**
     * Enregistre un nouvel article
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'resume' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'action' => 'required|in:draft,publish',
        ]);
        
        $data = $request->except(['image', 'action']);
        $data['utilisateur_id'] = Auth::id();
        $data['slug'] = Str::slug($request->titre);
        $data['est_publie'] = $request->action === 'publish';
        $data['date_publication'] = $request->action === 'publish' ? now() : null;
        
        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Créer le dossier s'il n'existe pas
            if (!Storage::exists('public/articles')) {
                Storage::makeDirectory('public/articles');
            }
            
            // Stocker l'image
            $path = $request->file('image')->store('public/articles');
            $data['image'] = str_replace('public/', '', $path);
            
            // Vérifier que l'image a bien été enregistrée
            if (!Storage::exists('public/' . $data['image'])) {
                return back()->with('error', 'Erreur lors de l\'enregistrement de l\'image.');
            }
        }
        
        $article = Article::create($data);
        
        // Message de succès personnalisé
        $message = $request->action === 'publish' 
            ? 'Article publié avec succès !' 
            : 'Article enregistré en brouillon.';
        
        // Redirection différente selon la route d'origine
        if (request()->routeIs('editor.*')) {
            return redirect()->route('editor.dashboard')
                ->with('success', $message);
        }
        
        return redirect()->route('articles.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    /**
     * Affiche un article spécifique
     */
    public function show(Article $article)
    {
        // Vérifier si l'utilisateur a le droit de voir l'article
        $user = Auth::user();
        if (!$user->isAdmin() && $article->utilisateur_id !== $user->id) {
            abort(403, 'Accès non autorisé à cet article.');
        }
        
        // Charger les relations nécessaires
        $article->load('auteur', 'categorie');
        
        return view('editor.articles.show', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Affiche le formulaire d'édition d'un article
     */
    public function edit($id)
    {
        // Récupérer l'article par son ID
        $article = Article::findOrFail($id);
        
        // Vérifier les permissions
        $user = Auth::user();
        if (!$user->isEditeur() || ($article->utilisateur_id !== $user->id && !$user->isAdmin())) {
            abort(403, 'Action non autorisée.');
        }
        
        $categories = Categorie::all();
        
        // Utiliser la vue d'édition de l'éditeur si l'utilisateur est un éditeur
        if (request()->routeIs('editor.*')) {
            return view('editor.articles.edit', compact('article', 'categories'));
        }
        
        return view('articles.edit', compact('article', 'categories'));
    }
    
    /**
     * Met à jour un article existant
     */
    public function update(Request $request, $id)
    {
        // Récupérer l'article par son ID
        $article = Article::findOrFail($id);
        // Vérifier les permissions
        $user = Auth::user();
        if (!$user->isAdmin() && $article->utilisateur_id !== $user->id) {
            abort(403, 'Action non autorisée.');
        }
        
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'resume' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'est_publie' => 'boolean',
        ]);
        
        $data = $request->except('image');
        
        // Mise à jour du slug si le titre a changé
        if ($article->titre !== $request->titre) {
            $data['slug'] = Str::slug($request->titre);
        }
        
        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Créer le dossier s'il n'existe pas
            if (!Storage::exists('public/articles')) {
                Storage::makeDirectory('public/articles');
            }
            
            // Supprimer l'ancienne image si elle existe
            if ($article->image && Storage::exists('public/' . $article->image)) {
                Storage::delete('public/' . $article->image);
            }
            
            // Stocker la nouvelle image
            $path = $request->file('image')->store('public/articles');
            $data['image'] = str_replace('public/', '', $path);
            
            // Vérifier que l'image a bien été enregistrée
            if (!Storage::exists('public/' . $data['image'])) {
                return back()->with('error', 'Erreur lors de l\'enregistrement de la nouvelle image.');
            }
        }
        
        $article->update($data);
        
        // Rediriger vers le tableau de bord de l'éditeur qui affiche la liste des articles
        return redirect()->route('editor.dashboard')
            ->with('success', 'Article mis à jour avec succès!');
    }

    /**
     * Bascule l'état de publication d'un article
     */
    public function togglePublish(Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur de l'article ou un administrateur
        if (auth()->user()->id !== $article->utilisateur_id && !auth()->user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $article->update([
            'est_publie' => !$article->est_publie,
            'date_publication' => $article->est_publie ? null : now()
        ]);

        $message = $article->wasChanged('est_publie') 
            ? ($article->est_publie ? 'Article publié avec succès !' : 'Article retiré de la publication.')
            : 'Aucune modification effectuée.';

        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Supprime un article
     */
    public function destroy(Article $article)
    {
        // Vérifier que l'utilisateur est l'auteur de l'article ou un administrateur
        if (auth()->user()->id !== $article->utilisateur_id && !auth()->user()->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        // Supprimer l'image associée si elle existe
        if ($article->image) {
            Storage::delete('public/' . $article->image);
        }
        
        $article->delete();
        
        return redirect()->route('articles.index')
            ->with('success', 'Article supprimé avec succès!');
    }
}
