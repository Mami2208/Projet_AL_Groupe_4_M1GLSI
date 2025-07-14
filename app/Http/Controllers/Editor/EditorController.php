<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditorController extends Controller
{
    /**
     * Afficher le tableau de bord de l'éditeur
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Récupérer les articles de l'utilisateur connecté
        $articles = Article::where('utilisateur_id', Auth::id())
            ->with('categorie')
            ->latest()
            ->paginate(10);

        // Récupérer les catégories pour le menu
        $categories = Categorie::all();

        return view('editor.dashboard', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }
}
