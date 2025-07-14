<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Article;

// Tester la connexion à la base de données
try {
    DB::connection()->getPdo();
    echo "Connexion à la base de données réussie.\n";
    
    // Compter les articles
    $count = Article::count();
    echo "Nombre d'articles dans la base de données : $count\n";
    
    // Afficher les 5 premiers articles
    $articles = Article::take(5)->get();
    
    if ($articles->isEmpty()) {
        echo "Aucun article trouvé dans la base de données.\n";
    } else {
        echo "\nDétails des articles :\n";
        echo str_repeat("-", 100) . "\n";
        
        foreach ($articles as $article) {
            echo "ID: " . $article->id . "\n";
            echo "Titre: " . $article->titre . "\n";
            echo "Publié: " . ($article->est_publie ? 'Oui' : 'Non') . "\n";
            echo "Date de publication: " . $article->date_publication . "\n";
            echo str_repeat("-", 100) . "\n";
        }
    }
    
} catch (\Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage() . "\n");
}
