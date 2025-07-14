<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoapController;
use App\Http\Controllers\API\ArticleApiController;
use App\Http\Controllers\API\DocumentationController;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route racine de l'API
Route::get('/', function () {
    $baseUrl = URL::to('/api');
    $now = now()->toDateTimeString();
    
    return response()->json([
        'api' => [
            'name' => 'API Actualités',
            'version' => '1.0.0',
            'status' => 'opérationnel',
            'timestamp' => $now,
            'documentation' => $baseUrl . '/documentation'
        ],
        'endpoints' => [
            'auth' => [
                'description' => 'Gestion de l\'authentification des utilisateurs',
                'endpoints' => [
                    [
                        'method' => 'POST',
                        'path' => $baseUrl . '/auth/register',
                        'description' => 'Enregistrer un nouvel utilisateur',
                        'auth_required' => false
                    ],
                    [
                        'method' => 'POST',
                        'path' => $baseUrl . '/auth/login',
                        'description' => 'Se connecter et obtenir un jeton d\'accès',
                        'auth_required' => false
                    ],
                    [
                        'method' => 'GET',
                        'path' => $baseUrl . '/auth/profile',
                        'description' => 'Obtenir le profil de l\'utilisateur connecté',
                        'auth_required' => true
                    ]
                ]
            ],
            'articles' => [
                'description' => 'Gestion des articles d\'actualités',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'path' => $baseUrl . '/articles',
                        'description' => 'Lister tous les articles',
                        'auth_required' => false
                    ],
                    [
                        'method' => 'GET',
                        'path' => $baseUrl . '/articles/categorie/{categorieSlug}',
                        'description' => 'Lister les articles par catégorie',
                        'auth_required' => false
                    ],
                    [
                        'method' => 'GET',
                        'path' => $baseUrl . '/articles/groupes-par-categorie',
                        'description' => 'Lister les articles groupés par catégorie',
                        'auth_required' => false
                    ]
                ]
            ],
            'documentation' => [
                'description' => 'Documentation de l\'API',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'path' => $baseUrl . '/documentation',
                        'description' => 'Documentation interactive (HTML)',
                        'auth_required' => false
                    ],
                    [
                        'method' => 'GET',
                        'path' => $baseUrl . '/documentation/json',
                        'description' => 'Spécification OpenAPI (JSON)',
                        'auth_required' => false
                    ],
                    [
                        'method' => 'GET',
                        'path' => $baseUrl . '/documentation/yaml',
                        'description' => 'Spécification OpenAPI (YAML)',
                        'auth_required' => false
                    ]
                ]
            ]
        ],
        'metadata' => [
            'server_time' => $now,
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'maintenance_mode' => app()->isDownForMaintenance()
        ]
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

// Routes d'authentification
Route::prefix('auth')->name('api.auth.')->group(function () {
    // Inscription
    Route::post('/register', [App\Http\Controllers\API\UtilisateurController::class, 'register'])
        ->name('register');
        
    // Connexion
    Route::post('/login', [App\Http\Controllers\API\UtilisateurController::class, 'login'])
        ->name('login');
    
    // Routes protégées par authentification
    Route::middleware('auth:sanctum')->group(function () {
        // Déconnexion
        Route::post('/logout', [App\Http\Controllers\API\UtilisateurController::class, 'logout'])
            ->name('logout');
            
        // Profil utilisateur
        Route::get('/profile', [App\Http\Controllers\API\UtilisateurController::class, 'profile'])
            ->name('profile');
            
        // Mise à jour du profil
        Route::put('/profile', [App\Http\Controllers\API\UtilisateurController::class, 'updateProfile'])
            ->name('profile.update');
    });
});

// Documentation de l'API - Swagger/OpenAPI
Route::prefix('documentation')->group(function () {
    // Documentation interactive (UI)
    Route::get('/', [\App\Http\Controllers\API\DocumentationController::class, 'index'])
        ->name('documentation');
        
    // Spécification OpenAPI (JSON)
    Route::get('/json', [\App\Http\Controllers\API\DocumentationController::class, 'json'])
        ->name('documentation.json');
        
    // Spécification OpenAPI (YAML)
    Route::get('/yaml', [\App\Http\Controllers\API\DocumentationController::class, 'yaml'])
        ->name('documentation.yaml');
});

// Routes pour les articles (API REST)
Route::prefix('articles')->group(function () {
    // Routes publiques
    Route::get('/', [ArticleApiController::class, 'index']);
    Route::get('/categorie/{categorieSlug}', [ArticleApiController::class, 'byCategory']);
    Route::get('/groupes-par-categorie', [ArticleApiController::class, 'byCategories']);
    
    // Routes protégées pour les éditeurs et administrateurs
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::post('/', [ArticleApiController::class, 'store'])->middleware('can:create,App\Models\Article');
        Route::put('/{article}', [ArticleApiController::class, 'update'])->middleware('can:update,article');
        Route::delete('/{article}', [ArticleApiController::class, 'destroy'])->middleware('can:delete,article');
    });
});

// Service SOAP
Route::prefix('soap')->group(function () {
    // Authentification
    Route::post('/authenticate', [SoapController::class, 'authenticate']);
    
    // Routes protégées par token
    Route::middleware('auth:sanctum')->group(function () {
        // Gestion des utilisateurs (admin uniquement)
        Route::post('/users/list', [SoapController::class, 'listUsers']);
        Route::post('/users/add', [SoapController::class, 'addUser']);
        Route::post('/users/update', [SoapController::class, 'updateUser']);
        Route::post('/users/delete', [SoapController::class, 'deleteUser']);
    });
});

// Route de test d'authentification
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
