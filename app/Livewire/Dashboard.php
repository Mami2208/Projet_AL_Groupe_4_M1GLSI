<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $stats = [
        'articles' => 0,
        'categories' => 0,
        'utilisateurs' => 0,
        'articles_recents' => [],
    ];

    public function mount()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $this->stats['articles'] = Article::count();
            $this->stats['categories'] = Categorie::count();
            $this->stats['utilisateurs'] = Utilisateur::count();
            $this->stats['articles_recents'] = Article::with(['categorie', 'auteur'])
                ->latest()
                ->take(5)
                ->get();
        } elseif ($user->isEditeur()) {
            $this->stats['articles'] = Article::where('utilisateur_id', $user->id)->count();
            $this->stats['categories'] = Categorie::count();
            $this->stats['articles_recents'] = Article::with(['categorie', 'auteur'])
                ->where('utilisateur_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        } else {
            // Pour les visiteurs, on ne montre que les articles publiés
            $this->stats['articles'] = Article::where('est_publie', true)->count();
            $this->stats['categories'] = Categorie::has('articles', '>', 0)->count();
            $this->stats['articles_recents'] = Article::with(['categorie', 'auteur'])
                ->where('est_publie', true)
                ->latest()
                ->take(5)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('components.layouts.app', [
                'header' => 'Tableau de bord',
            ]);
    }
}
