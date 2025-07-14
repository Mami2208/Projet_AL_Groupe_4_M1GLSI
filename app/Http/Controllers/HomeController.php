<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil avec la liste des articles
     */
    public function index(Request $request)
    {
        // Récupérer le terme de recherche s'il existe
        $search = $request->input('search');
        $categorieId = $request->input('categorie');
        
        // Construire la requête pour les articles publiés
        $query = Article::where('est_publie', true)
            ->with([
                'categorie' => function($query) {
                    $query->withDefault([
                        'nom' => 'Non classé',
                        'couleur' => 'gray',
                        'couleur_secondaire' => 'gray'
                    ]);
                },
                'auteur' => function($query) {
                    $query->withDefault([
                        'name' => 'Auteur inconnu',
                        'profile_photo_path' => null
                    ]);
                }
            ])
            ->latest('date_publication');
        
        // Appliquer le filtre de recherche si un terme est fourni
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('contenu', 'like', "%{$search}%")
                  ->orWhere('resume', 'like', "%{$search}%");
            });
        }
        
        // Appliquer le filtre par catégorie si une catégorie est sélectionnée
        if ($categorieId) {
            $query->where('categorie_id', $categorieId);
        }
        
        // Paginer les résultats (5 articles par page)
        $articles = $query->paginate(5)->appends([
            'search' => $search,
            'categorie' => $categorieId
        ]);
        
        // Récupérer toutes les catégories pour le menu
        $categories = Categorie::orderBy('nom')->get();
        
        return view('home', [
            'articles' => $articles,
            'categories' => $categories,
            'search' => $search,
            'selectedCategorie' => $categorieId
        ]);
    }
    
    /**
     * Affiche un article spécifique
     */
    public function show(Article $article)
    {
        // Vérifier si l'article est publié ou si l'utilisateur est connecté
        if (!$article->est_publie && !auth()->check()) {
            abort(404);
        }
        
        // Récupérer les articles similaires (même catégorie)
        $articlesSimilaires = Article::where('categorie_id', $article->categorie_id)
            ->where('id', '!=', $article->id)
            ->where('est_publie', true)
            ->take(3)
            ->get();
            
        return view('articles.show', [
            'article' => $article,
            'articlesSimilaires' => $articlesSimilaires
        ]);
    }
    
    /**
     * Affiche les articles d'une catégorie spécifique
     */
    public function category($categorie, Request $request)
    {
        // Journalisation pour débogage
        \Log::info('Méthode category appelée avec la catégorie : ' . $categorie);
        \Log::info('Requête complète : ', $request->all());
        
        // Récupérer la catégorie par son slug
        $categorie = Categorie::where('slug', $categorie)->firstOrFail();
        
        // Récupérer les articles publiés de cette catégorie avec pagination
        $articles = Article::where('categorie_id', $categorie->id)
            ->where('est_publie', true)
            ->with(['categorie', 'auteur'])
            ->latest('date_publication')
            ->paginate(10);
            
        // Récupérer toutes les catégories pour le menu
        $categories = Categorie::orderBy('nom')->get();
        
        // Vérifier si c'est une requête AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.articles', compact('articles'))->render(),
                'next' => $articles->nextPageUrl()
            ]);
        }
        
        return view('category', [
            'categorie' => $categorie,
            'articles' => $articles,
            'categories' => $categories
        ]);
    }
    
    /**
     * Afficher les articles d'un auteur
     */
    public function showAuthor($user, Request $request)
    {
        // Récupérer l'utilisateur par son ID ou son nom d'utilisateur
        $author = \App\Models\User::where('id', $user)
            ->orWhere('username', $user)
            ->firstOrFail();
        
        // Récupérer les articles publiés de cet auteur avec pagination
        $articles = Article::where('utilisateur_id', $author->id)
            ->where('est_publie', true)
            ->with(['categorie', 'auteur'])
            ->latest('date_publication')
            ->paginate(10);
            
        // Récupérer toutes les catégories pour le menu
        $categories = Categorie::orderBy('nom')->get();
        
        // Vérifier si c'est une requête AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.articles', compact('articles'))->render(),
                'next' => $articles->nextPageUrl()
            ]);
        }
        
        return view('author', [
            'author' => $author,
            'articles' => $articles,
            'categories' => $categories
        ]);
    }
}
