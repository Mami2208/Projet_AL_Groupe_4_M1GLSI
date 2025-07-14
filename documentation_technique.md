# Documentation Technique - Projet Actualités

## Table des matières

1. [Introduction](#introduction)
2. [Architecture du projet](#architecture-du-projet)
3. [Fonctionnalités principales](#fonctionnalités-principales)
4. [API REST](#api-rest)
5. [Service SOAP](#service-soap)
   - [Problématique CSRF et solution](#problématique-csrf-et-solution)
   - [Implémentation du serveur SOAP](#implémentation-du-serveur-soap)
   - [Client Python](#client-python)
6. [Authentification et sécurité](#authentification-et-sécurité)
7. [Modèles de données](#modèles-de-données)
8. [Interfaces utilisateur](#interfaces-utilisateur)
9. [Déploiement](#déploiement)
10. [Maintenance et évolution](#maintenance-et-évolution)

## Introduction

Le projet "Actualités" est une plateforme de gestion et de publication d'articles d'actualités développée avec Laravel. Il propose une interface web pour les utilisateurs finaux, un panneau d'administration pour les gestionnaires de contenu, ainsi que des API REST et SOAP pour l'intégration avec d'autres systèmes.

**Technologies principales :**
- Backend : PHP 8.2.4avec Laravel 12.19.3
- Frontend : Blade, Livewire, TailwindCSS
- Base de données : MySQL
- API : REST et SOAP (les applications clientes peuvent choisir l'une ou l'autre selon leurs besoins)
- Client API : Python (exemple d'implémentation utilisant SOAP)

## Architecture du projet

Le projet suit l'architecture MVC (Modèle-Vue-Contrôleur) de Laravel avec quelques adaptations pour intégrer Livewire et les services API.

### Structure des répertoires

```
projet-actualite/
├── app/                    # Code source principal
│   ├── Console/           # Commandes artisan personnalisées
│   ├── Http/              # Contrôleurs, Middleware, Requests
│   │   ├── Controllers/   # Contrôleurs de l'application
│   │   │   ├── API/       # Contrôleurs pour l'API REST
│   │   │   ├── Admin/     # Contrôleurs pour l'administration
│   │   │   └── ...
│   │   ├── Middleware/    # Middleware de l'application
│   │   └── ...
│   ├── Livewire/          # Composants Livewire
│   ├── Models/            # Modèles Eloquent
│   └── Services/          # Services métier et WSDL
├── bootstrap/             # Fichiers d'amorçage de Laravel
├── config/                # Configuration de l'application
├── database/              # Migrations et seeds
├── public/                # Fichiers publics accessibles directement
├── resources/             # Ressources frontend
│   ├── css/              # Fichiers CSS
│   ├── js/               # Fichiers JavaScript
│   └── views/            # Templates Blade
│       ├── components/   # Composants Blade
│       ├── layouts/      # Layouts Blade
│       └── livewire/     # Templates pour composants Livewire
├── routes/                # Définition des routes
│   ├── api.php           # Routes API REST
│   ├── web.php           # Routes web
│   └── ...
└── tests/                 # Tests automatisés
```

## Fonctionnalités principales

1. **Gestion des articles**
   - Création, édition, publication et suppression d'articles
   - Catégorisation des articles
   - Gestion des médias (images)

2. **Gestion des utilisateurs**
   - authentification
   - Gestion des rôles (visiteur, éditeur, administrateur)
   - Profils utilisateurs

3. **Interface d'administration**
   - Tableau de bord pour les administrateurs
   - Gestion des utilisateurs et des rôles
   - Statistiques et rapports

4. **API REST**
   - Endpoints pour accéder aux articles et catégories
   - Authentification via Sanctum (tokens API)
   - Documentation interactive (OpenAPI/Swagger)

5. **Service SOAP**
   - Authentification des utilisateurs
   - Gestion des utilisateurs (admin uniquement)
   - Intégration avec client Python

## API REST

L'API REST est implémentée selon les standards RESTful et utilise JSON comme format d'échange de données.

### Points d'entrée principaux

#### Authentification
- `POST /api/auth/login` : Connexion et obtention d'un token
- `GET /api/auth/profile` : Récupération du profil utilisateur (authentifié)

#### Articles
- `GET /api/articles` : Liste de tous les articles
- `GET /api/articles/categorie/{categorieSlug}` : Articles par catégorie
- `GET /api/articles/groupes-par-categorie` : Articles groupés par catégorie
- `POST /api/articles` : Création d'un article (authentifié)
- `PUT /api/articles/{article}` : Mise à jour d'un article (authentifié)
- `DELETE /api/articles/{article}` : Suppression d'un article (authentifié)

#### Documentation
- `GET /api/documentation` : Documentation interactive (HTML)
- `GET /api/documentation/json` : Spécification OpenAPI (JSON)
- `GET /api/documentation/yaml` : Spécification OpenAPI (YAML)

### Authentification API REST

L'API utilise Laravel Sanctum pour l'authentification par token. Les tokens sont générés lors de la connexion et doivent être inclus dans l'en-tête `Authorization` sous forme de `Bearer {token}` pour les requêtes authentifiées.

## Intégration des applications clientes

Le projet offre deux méthodes d'intégration pour les applications clientes : l'API REST et le service SOAP. Les applications clientes peuvent choisir l'interface qui convient le mieux à leurs besoins techniques et fonctionnels.

### Choix entre REST et SOAP

Pour permettre aux applications clientes de choisir entre REST et SOAP, nous recommandons l'approche suivante :

1. **Implémenter des classes de service distinctes** pour chaque type d'API (REST et SOAP)
2. **Utiliser une factory** pour créer le service approprié en fonction du choix de l'utilisateur
3. **Exposer une interface commune** pour que le reste de l'application puisse interagir avec n'importe quel service de manière transparente

L'application cliente Python fournie avec le projet démontre cette approche en permettant à l'utilisateur de choisir entre REST et SOAP lors de la connexion.

## Service SOAP

Le service SOAP permet l'intégration avec des systèmes tiers via le protocole SOAP. Il est particulièrement utile pour les opérations complexes nécessitant une structure de données fortement typée, comme la gestion des utilisateurs.

### Points d'entrée SOAP

- `/soap/wsdl` : Fichier WSDL décrivant le service
- `/soap` : Endpoint principal du service SOAP

### Méthodes SOAP disponibles

- `authenticate(email, password)` : Authentification et obtention d'un token
- `listUsers(token)` : Liste des utilisateurs (admin uniquement)
- `addUser(name, email, password, role)` : Ajout d'un utilisateur (admin uniquement)
- `updateUser(userId, data)` : Mise à jour d'un utilisateur (admin uniquement)
- `deleteUser(userId)` : Suppression d'un utilisateur (admin uniquement)

### Problématique CSRF et solution

Le service SOAP a rencontré des problèmes avec la protection CSRF de Laravel, générant des erreurs 419 "Page Expired" lors des appels depuis le client Python. Pour résoudre ce problème, une solution robuste a été mise en place :

#### 1. Middleware SoapRequestMiddleware

Ce middleware identifie les requêtes SOAP basées sur plusieurs critères :
- Le chemin de la requête (commence par 'soap')
- Les en-têtes Content-Type (text/xml, application/soap+xml, application/xml)
- La présence d'un en-tête SOAPAction
- Le contenu XML avec balises SOAP

```php
// Extrait de SoapRequestMiddleware.php
public function handle(Request $request, Closure $next)
{
    // Vérifier si c'est une requête SOAP basée sur le contenu et les en-têtes
    $contentType = $request->header('Content-Type');
    $soapAction = $request->header('SOAPAction');
    $content = $request->getContent();
    
    $isSoapRequest = false;
    
    // Vérifier si c'est une requête SOAP basée sur le chemin
    if (str_starts_with($request->path(), 'soap')) {
        $isSoapRequest = true;
    }
    
    // Vérifier si c'est une requête SOAP basée sur le Content-Type
    if ($contentType && (
        str_contains($contentType, 'text/xml') || 
        str_contains($contentType, 'application/soap+xml') ||
        str_contains($contentType, 'application/xml')
    )) {
        $isSoapRequest = true;
    }
    
    // [...]
    
    if ($isSoapRequest) {
        // Marquer la requête comme étant une requête SOAP
        $request->attributes->set('is_soap_request', true);
        // Désactiver la vérification CSRF pour cette requête
        $request->attributes->set('csrf_disabled', true);
    }
    
    return $next($request);
}
```

#### 2. Extension du middleware VerifyCsrfToken

Le middleware VerifyCsrfToken a été étendu pour détecter les requêtes SOAP et les exempter de la vérification CSRF :

```php
// Extrait de VerifyCsrfToken.php
protected function shouldPassThrough($request)
{
    // Si la requête est marquée comme étant une requête SOAP par notre middleware
    if ($request->attributes->get('is_soap_request') === true || $request->attributes->get('csrf_disabled') === true) {
        Log::info('VerifyCsrfToken - Requête SOAP détectée, CSRF désactivé');
        return true;
    }
    
    // Vérifier manuellement si c'est une requête SOAP basée sur le Content-Type
    $contentType = $request->header('Content-Type');
    if ($contentType && (
        str_contains($contentType, 'text/xml') || 
        str_contains($contentType, 'application/soap+xml') ||
        str_contains($contentType, 'application/xml')
    )) {
        Log::info('VerifyCsrfToken - Content-Type XML détecté, CSRF désactivé');
        return true;
    }
    
    // [...]
    
    return parent::shouldPassThrough($request);
}
```

#### 3. Configuration du Kernel HTTP

Le middleware SoapRequestMiddleware est exécuté avant VerifyCsrfToken dans le groupe de middleware 'web' :

```php
// Extrait de Kernel.php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\SoapRequestMiddleware::class, // Doit s'exécuter avant VerifyCsrfToken
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
    // [...]
];
```

### Implémentation du serveur SOAP

Le serveur SOAP est implémenté via le contrôleur `SoapServerController` qui utilise la classe native `SoapServer` de PHP :

```php
// Extrait de SoapServerController.php
public function handle()
{
    $wsdlPath = app_path('Services/UserService.wsdl');
    
    if (!file_exists($wsdlPath)) {
        abort(404, 'WSDL file not found');
    }
    
    $server = new \SoapServer($wsdlPath, [
        'uri' => 'http://localhost/soap',
        'soap_version' => SOAP_1_2,
        'cache_wsdl' => WSDL_CACHE_NONE,
    ]);
    
    // Enregistrer les méthodes du contrôleur SOAP
    $server->setObject($this->soapController);
    
    // Démarrer le traitement de la requête SOAP
    ob_start();
    $server->handle();
    $content = ob_get_clean();
    
    return response($content, 200, [
        'Content-Type' => 'application/soap+xml; charset=utf-8'
    ]);
}
```

### Client Python

Un client Python utilisant la bibliothèque `suds` a été développé pour tester et interagir avec le service SOAP :

```python
# Extrait de test_soap.py
from suds.client import Client
from suds.sax.element import Element
import logging

# Activer les logs pour le débogage
logging.basicConfig(level=logging.INFO)
logging.getLogger('suds.client').setLevel(logging.DEBUG)
logging.getLogger('suds.transport').setLevel(logging.DEBUG)

def test_soap_connection():
    """Test de connexion au service SOAP"""
    print("Tentative de connexion au service SOAP...")
    
    try:
        # Créer un client SOAP avec le WSDL
        client = Client('http://localhost:8000/soap/wsdl')
        
        # Tester l'authentification
        print("\nTest d'authentification...")
        result = client.service.authenticate('admin@example.com', 'password')
        
        if hasattr(result, 'success') and result.success:
            print(f"Authentification réussie! Token: {result.token}")
            
            # Créer un en-tête SOAP avec le token
            token_header = Element('token')
            token_header.setText(result.token)
            
            # Appel avec le token dans l'en-tête
            users_result = client.service.listUsers(__inject={'soapheaders': token_header})
            print(f"Résultat: {users_result}")
            
    except Exception as e:
        print(f"Erreur: {str(e)}")
```

## Authentification et sécurité

### Système d'authentification

Le projet utilise plusieurs mécanismes d'authentification :

1. **Authentification web** : Utilise le système d'authentification standard de Laravel avec sessions.
2. **Authentification API REST** : Utilise Laravel Sanctum pour l'authentification par token.
3. **Authentification SOAP** : Utilise également Sanctum mais avec un middleware personnalisé (`SoapAuth`) pour extraire le token des en-têtes SOAP.

### Middleware de sécurité

Plusieurs middleware sont utilisés pour sécuriser l'application :

- `auth` : Vérifie si l'utilisateur est authentifié
- `auth:sanctum` : Vérifie l'authentification via token Sanctum
- `verified` : Vérifie si l'email de l'utilisateur est vérifié
- `can:isAdmin` : Vérifie si l'utilisateur a le rôle d'administrateur
- `can:isEditor` : Vérifie si l'utilisateur a le rôle d'éditeur
- `soap.auth` : Middleware personnalisé pour l'authentification SOAP

### Gestion des rôles

Le système implémente une gestion des rôles simple avec trois niveaux :
- **visiteur** : Utilisateur standard avec accès en lecture uniquement
- **editeur** : Peut créer et gérer des articles
- **admin** : Accès complet à toutes les fonctionnalités

## Modèles de données

### Principaux modèles

1. **Utilisateur** (Utilisateur.php)
   - Gestion des utilisateurs et de l'authentification
   - Relations avec les articles (auteur)

2. **Article** (Article.php)
   - Gestion des articles d'actualité
   - Relations avec les catégories et les utilisateurs

3. **Catégorie** (Categorie.php)
   - Classification des articles
   - Relation many-to-many avec les articles

## Interfaces utilisateur

### Frontend public

Interface destinée aux visiteurs du site, permettant de consulter les articles d'actualité.

### Interface d'administration

Panneau d'administration pour les éditeurs et administrateurs, permettant de gérer le contenu et les utilisateurs.

### Composants Livewire

Le projet utilise Livewire pour créer des interfaces dynamiques sans avoir à écrire de JavaScript :

- `Dashboard.php` : Tableau de bord interactif
- `Auth/Login.php` : Formulaire de connexion interactif

## Déploiement

### Prérequis

- PHP 8.2.4 ou supérieur
- MySQL 5.7 ou supérieur
- Composer
- Node.js et NPM (pour les assets frontend)
- Serveur web (Apache, Nginx)

### Étapes de déploiement

1. Cloner le dépôt
2. Installer les dépendances PHP : `composer install`
3. Installer les dépendances JavaScript : `npm install`
4. Compiler les assets : `npm run build`
5. Configurer le fichier `.env`
6. Générer la clé d'application : `php artisan key:generate`
7. Exécuter les migrations : `php artisan migrate`
8. Créer un utilisateur administrateur : `php artisan app:create-admin-user`

## Maintenance et évolution

### Logs et débogage

Le projet utilise le système de logs de Laravel pour faciliter le débogage, notamment pour les requêtes SOAP :

```php
Log::info('SoapRequestMiddleware - Content-Type: ' . $contentType);
Log::info('SoapRequestMiddleware - SOAPAction: ' . $soapAction);
```

### Améliorations futures

1. **Optimisation des performances**
   - Mise en cache des requêtes API fréquentes
   - Optimisation des requêtes de base de données

2. **Sécurité renforcée**
   - Implémentation de l'authentification à deux facteurs
   - Audit de sécurité complet

3. **Fonctionnalités additionnelles**
   - Système de commentaires pour les articles
   - Intégration avec les réseaux sociaux
   - API GraphQL en complément des API REST et SOAP
