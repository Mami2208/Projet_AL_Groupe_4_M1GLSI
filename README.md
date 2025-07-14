# Projet Actualités

## Présentation

Projet Actualités est une plateforme complète de gestion et de publication d'articles d'actualités développée avec Laravel. Cette application web permet la création, la gestion et la consultation d'articles organisés par catégories, avec un système d'authentification et de gestion des rôles utilisateurs.

## Fonctionnalités principales

- **Gestion des articles** : création, édition, publication et suppression d'articles
- **Catégorisation** : organisation des articles par catégories
- **Authentification** : système de connexion sécurisé
- **Gestion des rôles** : trois niveaux d'accès (visiteur, éditeur, administrateur)
- **API REST** : accès programmatique aux données via une API RESTful
- **Service SOAP** : intégration avec des systèmes tiers via SOAP
- **Interface d'administration** : tableau de bord pour la gestion du contenu

## Technologies utilisées

- **Backend** : PHP 8.0 avec Laravel 10.1
- **Frontend** : Blade, Livewire, TailwindCSS
- **Base de données** : MySQL
- **API** : REST et SOAP (les applications clientes peuvent choisir l'une ou l'autre selon leurs besoins)
- **Client API** : Python (implémentation permettant de choisir entre REST et SOAP)

## Installation

### Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Composer
- Node.js et NPM

### Étapes d'installation

1. Cloner le dépôt :
   ```bash
   git clone [URL_DU_DEPOT]
   cd projet-actualite
   ```

2. Installer les dépendances PHP :
   ```bash
   composer install
   ```

3. Installer les dépendances JavaScript :
   ```bash
   npm install
   ```

4. Compiler les assets :
   ```bash
   npm run build
   ```

5. Copier le fichier d'environnement et configurer les variables :
   ```bash
   cp .env.example .env
   # Modifier le fichier .env avec vos paramètres de base de données
   ```

6. Générer la clé d'application :
   ```bash
   php artisan key:generate
   ```

7. Exécuter les migrations et les seeders :
   ```bash
   php artisan migrate --seed
   ```

8. Créer un utilisateur administrateur :
   ```bash
   php artisan app:create-admin-user
   ```

9. Lancer le serveur de développement :
   ```bash
   php artisan serve
   ```

## Structure du projet

- `app/` : Code source principal
  - `Http/Controllers/` : Contrôleurs de l'application
  - `Http/Middleware/` : Middleware, notamment pour la gestion des requêtes SOAP
  - `Models/` : Modèles Eloquent
  - `Services/` : Services métier et WSDL
- `resources/views/` : Templates Blade et composants Livewire
- `routes/` : Définition des routes (web.php, api.php)
- `database/migrations/` : Migrations de base de données

## API REST

L'API REST permet d'accéder aux données du projet via des requêtes HTTP standard.

### Points d'entrée principaux

- `POST /api/auth/login` : Connexion et obtention d'un token
- `GET /api/articles` : Liste de tous les articles
- `GET /api/articles/categorie/{categorieSlug}` : Articles par catégorie

Consultez la documentation complète à l'adresse `/api/documentation` après avoir lancé le serveur.

## Service SOAP

Le projet intègre un service SOAP pour l'interopérabilité avec d'autres systèmes.

### Points d'entrée SOAP

- `/soap/wsdl` : Fichier WSDL décrivant le service
- `/soap` : Endpoint principal du service SOAP

### Client Python

Un client Python utilisant la bibliothèque `suds` est fourni pour tester le service SOAP :

```bash
python test_soap.py
```

## Solution au problème CSRF pour SOAP

Le projet implémente une solution robuste pour gérer les requêtes SOAP sans être bloqué par la protection CSRF de Laravel. Cette solution utilise un middleware personnalisé (`SoapRequestMiddleware`) qui détecte les requêtes SOAP basées sur leur contenu et leurs en-têtes, puis les marque pour être exemptées de la vérification CSRF.

## Documentation

Pour une documentation technique complète, consultez le fichier `documentation_technique.md` à la racine du projet.

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.
# Projet_AL_Groupe_4_M1GLSI
