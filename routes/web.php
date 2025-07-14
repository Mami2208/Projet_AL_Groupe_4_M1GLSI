<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SoapServerController;

// Routes d'authentification
require __DIR__.'/auth.php';

// Routes SOAP
Route::get('/soap/wsdl', [\App\Http\Controllers\SoapServerController::class, 'wsdl'])
    ->name('soap.wsdl');

Route::match(['GET', 'POST'], '/soap', [\App\Http\Controllers\SoapServerController::class, 'handle'])
    ->name('soap.server');

// Routes de réinitialisation de mot de passe (gérées par Laravel Fortify)
Route::get('/forgot-password', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', 'App\Http\Controllers\Auth\ResetPasswordController@reset')
    ->middleware('guest')
    ->name('password.update');


// Routes de vérification d'email
Route::get('/email/verify', 'App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
Route::post('/email/resend', 'App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');

// Routes de test
Route::get('/test', [TestController::class, 'showTestForm'])->name('test');
Route::post('/test', [TestController::class, 'testPost'])->name('test.post');

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/articles/{article:slug}', [HomeController::class, 'show'])->name('articles.show');
// Afficher les articles d'une catégorie spécifique
Route::get('/categorie/{categorie}', [HomeController::class, 'category'])
    ->name('categorie.show');

// Routes protégées pour les éditeurs et administrateurs
Route::middleware(['auth'])->group(function () {
    // Routes pour les articles (éditeurs et admin)
    Route::resource('articles', \App\Http\Controllers\ArticleController::class)
        ->except(['show'])
        ->middleware('can:isEditor');
        
    // Routes spécifiques pour les administrateurs
    Route::middleware(['can:isAdmin'])->group(function () {
        // Gestion des utilisateurs
        Route::resource('users', \App\Http\Controllers\UserController::class);
        
        // Gestion des catégories
        Route::resource('categories', \App\Http\Controllers\CategorieController::class)->except(['show']);
        
        // Génération de token API
        Route::post('users/{user}/generate-token', [\App\Http\Controllers\UserController::class, 'generateApiToken'])
            ->name('users.generate-token');
    });
});

// Afficher le profil d'un auteur
Route::get('/auteur/{user}', [HomeController::class, 'showAuthor'])
    ->name('auteur.show');


// Routes protégées par authentification
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
        // Tableau de bord utilisateur
    Route::get('/mon-compte', function () {
        return view('dashboard');
    })->name('user.dashboard');
    
    // Tableau de bord des éditeurs
    Route::prefix('editor')->name('editor.')->middleware(['can:isEditor'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Editor\EditorController::class, 'dashboard'])->name('dashboard');
        
        // Routes pour les articles
        Route::resource('articles', \App\Http\Controllers\ArticleController::class);
        
        // Publication/Dépublication d'un article
        Route::patch('articles/{article}/toggle-publish', [\App\Http\Controllers\ArticleController::class, 'togglePublish'])
            ->name('articles.toggle-publish');
            
        // Route de mise à jour explicite
        Route::put('articles/{article}', [\App\Http\Controllers\ArticleController::class, 'update'])
            ->name('articles.update');
        
        // Routes pour les catégories
        Route::resource('categories', \App\Http\Controllers\Editor\CategorieController::class)
            ->parameters(['categories' => 'categorie']);
    });
    // Routes d'administration
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['auth', 'verified', 'can:isAdmin'])
        ->group(function () {
            // Tableau de bord admin
            Route::get('/', function () {
                return redirect()->route('admin.dashboard');
            });
            
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            
            // Gestion des articles
            Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);
            
            // Gestion des catégories
            Route::resource('categories', \App\Http\Controllers\Admin\CategorieController::class);
            
            // Gestion des utilisateurs
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
            
            // Autres routes d'administration
            Route::middleware('can:access-admin')->group(function () {
            // Redirection de /admin vers /admin/dashboard
            Route::get('/', function () {
                return redirect()->route('admin.dashboard');
            });
            
            // Tableau de bord
            Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
            
            // Gestion des utilisateurs
            Route::resource('users', App\Http\Controllers\Admin\UserController::class);
            
            // Gestion des rôles et permissions
            Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
            
            // Gestion des jetons d'API
            Route::prefix('api')->name('api.')->group(function () {
                Route::get('/tokens', [\App\Http\Controllers\Admin\ApiTokenController::class, 'index'])
                    ->name('tokens.index');
                Route::post('/tokens', [\App\Http\Controllers\Admin\ApiTokenController::class, 'store'])
                    ->name('tokens.store');
                Route::delete('/tokens/{token}', [\App\Http\Controllers\Admin\ApiTokenController::class, 'destroy'])
                    ->name('tokens.destroy');
            });
            Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class);
            
            // Gestion des jetons API
            Route::get('/api-tokens', [App\Http\Controllers\Admin\ApiTokenController::class, 'index'])->name('api-tokens.index');
            Route::post('/api-tokens', [App\Http\Controllers\Admin\ApiTokenController::class, 'store'])->name('api-tokens.store');
            Route::delete('/api-tokens/{tokenId}', [App\Http\Controllers\Admin\ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
            
            // Gestion des services API
            Route::get('/services', [App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services.index');
            
            // Gestion des jetons d'API
            Route::post('/tokens/generate', [App\Http\Controllers\Admin\AdminController::class, 'generateToken'])
                ->name('tokens.generate');
                
            Route::delete('/tokens/revoke/{tokenId?}', [App\Http\Controllers\Admin\AdminController::class, 'revokeToken'])
                ->name('tokens.revoke');
                
            // Anciennes routes pour compatibilité (à supprimer dans une version future)
            Route::post('/token/generate', [App\Http\Controllers\Admin\AdminController::class, 'oldGenerateToken'])
                ->name('token.generate');
                
            Route::delete('/token/revoke', [App\Http\Controllers\Admin\AdminController::class, 'revokeLegacyToken'])
                ->name('token.revoke');
                
            // Gestion des catégories
            Route::resource('categories', 'App\Http\Controllers\Admin\CategorieController')
                ->except(['show']);
                
                // Gestion des articles (admin)
                Route::resource('articles', 'App\Http\Controllers\Admin\ArticleController')
                    ->except(['show']);
                    
                // Gestion des services API
                Route::get('services', [\App\Http\Controllers\Admin\ServiceController::class, 'index'])
                    ->name('services.index');
            });
        });
    
    // Route pour afficher les logs (uniquement en développement)
    if (app()->environment('local')) {
        Route::get('/logs', [LogController::class, 'show'])->name('logs.show');
    }
});
