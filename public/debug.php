<?php
// Activer l'affichage des erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure l'autoloader de Laravel
require __DIR__.'/../vendor/autoload.php';

// Démarrer l'application Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// Exécuter l'application
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Afficher les erreurs PHP
if (function_exists('error_get_last')) {
    $error = error_get_last();
    if ($error !== null) {
        echo "<h2>Dernière erreur PHP :</h2>";
        echo "<pre>";
        print_r($error);
        echo "</pre>";
    } else {
        echo "<p>Aucune erreur PHP détectée.</p>";
    }
}

// Tester la connexion à la base de données
try {
    $pdo = new PDO(
        'mysql:host='.env('DB_HOST', '127.0.0.1').';dbname='.env('DB_DATABASE', 'forge'),
        env('DB_USERNAME', 'forge'),
        env('DB_PASSWORD', '')
    );
    echo "<p style='color:green;'>Connexion à la base de données réussie</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erreur de connexion à la base de données : " . $e->getMessage() . "</p>";
    echo "<p>Configuration actuelle :</p>";
    echo "<ul>";
    echo "<li>Hôte : " . env('DB_HOST', '127.0.0.1') . "</li>";
    echo "<li>Base de données : " . env('DB_DATABASE', 'forge') . "</li>";
    echo "<li>Utilisateur : " . env('DB_USERNAME', 'forge') . "</li>";
    echo "</ul>";
}

// Tester si le modèle Utilisateur existe
try {
    if (class_exists('App\Models\Utilisateur')) {
        $userCount = \App\Models\Utilisateur::count();
        echo "<p style='color:green;'>Modèle Utilisateur trouvé. Nombre d'utilisateurs : $userCount</p>";
    } else {
        echo "<p style='color:red;'>Le modèle Utilisateur n'existe pas</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Erreur avec le modèle Utilisateur : " . $e->getMessage() . "</p>";
}

// Tester si le modèle Article existe
try {
    if (class_exists('App\Models\Article')) {
        $articleCount = \App\Models\Article::count();
        echo "<p style='color:green;'>Modèle Article trouvé. Nombre d'articles : $articleCount</p>";
    } else {
        echo "<p style='color:red;'>Le modèle Article n'existe pas</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Erreur avec le modèle Article : " . $e->getMessage() . "</p>";
}

// Afficher les informations du serveur
echo "<h2>Informations du serveur :</h2>";
echo "<pre>";
echo "PHP Version : " . phpversion() . "\n";
echo "Extensions PHP chargées : " . implode(", ", get_loaded_extensions()) . "\n";
echo "</pre>";

// Afficher les variables d'environnement (sans les mots de passe)
echo "<h2>Variables d'environnement :</h2>";
echo "<pre>";
$envVars = [
    'APP_ENV', 'APP_DEBUG', 'APP_URL',
    'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME',
    'CACHE_DRIVER', 'SESSION_DRIVER', 'QUEUE_DRIVER'
];

foreach ($envVars as $var) {
    echo "$var = " . env($var, 'non défini') . "\n";
}
echo "</pre>";
