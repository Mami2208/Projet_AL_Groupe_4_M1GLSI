<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Session;

class SoapAuth
{
    /**
     * Handle an incoming SOAP request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Pour le débogage
        Log::info('SOAP Request Path: ' . $request->path());
        Log::info('SOAP Request Method: ' . $request->method());
        Log::info('Headers: ' . json_encode($request->headers->all()));
        
        // Dump du contenu de la requête pour le débogage
        $content = $request->getContent();
        Log::info('SOAP Content: ' . $content);
        
        // Désactiver complètement la vérification CSRF pour les requêtes SOAP
        if ($request->path() === 'soap' || $request->path() === 'soap/wsdl') {
            // Marquer la requête comme ayant passé la vérification CSRF
            $request->headers->set('X-CSRF-TOKEN', 'soap-bypass');
            Session::put('_token', 'soap-bypass');
            
            Log::info('Mode débogage: Requête SOAP autorisée sans authentification et sans CSRF');
            
            // Connecter automatiquement un utilisateur admin pour les tests
            $admin = Utilisateur::where('role', 'admin')->first();
            if ($admin) {
                Auth::login($admin);
                Log::info('Utilisateur admin connecté automatiquement: ' . $admin->email);
            }
            
            return $next($request);
        }

        // Essayer de récupérer le token de l'en-tête HTTP
        $token = $request->header('Authorization');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
        }
        
        // Si pas de token dans l'en-tête, essayer de l'extraire du XML SOAP
        if (!$token) {
            $content = $request->getContent();
            Log::info('SOAP Content: ' . $content);
            
            // Essayer d'extraire le token du XML
            $tokenMatch = [];
            if (preg_match('/<token>(.*?)<\/token>/', $content, $tokenMatch)) {
                $token = $tokenMatch[1] ?? null;
                Log::info('Token extrait du XML: ' . $token);
            }
        }
        
        if (!$token) {
            Log::warning('Token d\'authentification manquant');
            return response([
                'success' => false,
                'message' => 'Token d\'authentification manquant',
            ], 401);
        }
        
        // Vérifier le token avec Sanctum
        $accessToken = PersonalAccessToken::findToken($token);
        
        if (!$accessToken) {
            Log::warning('Token invalide ou expiré: ' . $token);
            return response([
                'success' => false,
                'message' => 'Token invalide ou expiré',
            ], 401);
        }

        // Authentifier l'utilisateur
        $user = $accessToken->tokenable;
        Log::info('Utilisateur authentifié: ' . $user->email);
        Auth::login($user);

        return $next($request);
    }
}
