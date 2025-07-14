<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class DocumentationController extends Controller
{
    /**
     * Affiche la documentation de l'API
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('api.documentation');
    }

    /**
     * Retourne la spécification OpenAPI au format JSON
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function json()
    {
        $jsonPath = public_path('api-docs/openapi.json');
        
        // Générer la documentation si elle n'existe pas ou si on est en mode développement
        if (!File::exists($jsonPath) || config('app.env') === 'local') {
            Artisan::call('api:docs');
        }
        
        if (!File::exists($jsonPath)) {
            return response()->json([
                'error' => 'Documentation non disponible',
                'message' => 'La documentation de l\'API n\'a pas pu être générée.'
            ], 500);
        }
        
        return response()->file($jsonPath, [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    /**
     * Retourne la spécification OpenAPI au format YAML
     *
     * @return \Illuminate\Http\Response
     */
    public function yaml()
    {
        $yamlPath = public_path('api-docs/openapi.yaml');
        
        // Générer la documentation si elle n'existe pas ou si on est en mode développement
        if (!File::exists($yamlPath) || config('app.env') === 'local') {
            Artisan::call('api:docs');
        }
        
        if (!File::exists($yamlPath)) {
            return response()->json([
                'error' => 'Documentation non disponible',
                'message' => 'La documentation de l\'API n\'a pas pu être générée.'
            ], 500, ['Content-Type' => 'application/json']);
        }
        
        return response()->file($yamlPath, [
            'Content-Type' => 'application/x-yaml',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }
}
