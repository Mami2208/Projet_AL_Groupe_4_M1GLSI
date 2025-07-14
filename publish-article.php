<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Article;

// Mettre à jour l'article existant pour le marquer comme publié
$updated = Article::where('id', 1)->update([
    'est_publie' => true,
    'date_publication' => now()
]);

if ($updated) {
    echo "L'article a été marqué comme publié avec succès.\n";
    
    // Afficher les détails de l'article mis à jour
    $article = Article::find(1);
    echo "ID: " . $article->id . "\n";
    echo "Titre: " . $article->titre . "\n";
    echo "Publié: " . ($article->est_publie ? 'Oui' : 'Non') . "\n";
    echo "Date de publication: " . $article->date_publication . "\n";
} else {
    echo "Aucun article trouvé avec l'ID 1.\n";
}
