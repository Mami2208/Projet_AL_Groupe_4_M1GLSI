<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Test PHP fonctionnel";

// Test de connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=nom_de_votre_base', 'votre_utilisateur', 'votre_mot_de_passe');
    echo "Connexion à la base de données réussie";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
