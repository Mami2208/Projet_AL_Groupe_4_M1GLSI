<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administration
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = [
            'articles' => [
                'total' => Article::count(),
                'publies' => Article::where('est_publie', true)->count(),
                'en_attente' => Article::where('est_publie', false)->count(),
            ],
            'categories' => [
                'total' => Categorie::count(),
                'avec_articles' => Categorie::has('articles')->count(),
            ],
            'utilisateurs' => [
                'total' => Utilisateur::count(),
                'administrateurs' => Utilisateur::where('role', 'admin')->count(),
                'editeurs' => Utilisateur::where('role', 'editeur')->count(),
                'visiteurs' => Utilisateur::where('role', 'visiteur')->count(),
            ],
        ];

        // Derniers articles
        $derniersArticles = Article::with(['auteur', 'categorie'])
            ->latest('date_publication')
            ->take(5)
            ->get();

        // Articles les plus récents (remplace les articles populaires en attendant l'implémentation du suivi des vues)
        $articlesPopulaires = Article::with(['auteur', 'categorie'])
            ->where('est_publie', true)
            ->latest('date_publication')
            ->take(5)
            ->get();

        // Statistiques mensuelles
        $statistiquesMensuelles = Article::select(
                DB::raw('DATE_FORMAT(date_publication, "%Y-%m") as mois'),
                DB::raw('count(*) as total')
            )
            ->where('date_publication', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->pluck('total', 'mois');

        // Récupérer la liste des utilisateurs avec pagination
        $users = Utilisateur::latest()->paginate(10);
        
        // Données des services API
        $services = [
            [
                'name' => 'API REST',
                'status' => 'en_ligne',
                'endpoint' => url('/api'),
                'documentation' => url('/api/documentation'),
                'description' => 'API RESTful pour accéder aux ressources de l\'application',
                'version' => '1.0.0'
            ],
            [
                'name' => 'API SOAP',
                'status' => 'en_ligne',
                'endpoint' => url('/soap'),
                'documentation' => url('/soap/wsdl'),
                'description' => 'Service SOAP pour l\'intégration avec des systèmes hérités',
                'version' => '1.0.0'
            ]
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'derniersArticles' => $derniersArticles,
            'articlesPopulaires' => $articlesPopulaires,
            'statistiquesMensuelles' => $statistiquesMensuelles,
            'users' => $users,
            'services' => $services,
        ]);
    }
}
