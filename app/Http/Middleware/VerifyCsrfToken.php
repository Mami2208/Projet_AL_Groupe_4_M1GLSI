<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Routes API
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'admin/*',
        // Routes SOAP - exclues de la vérification CSRF
        'soap',
        'soap/*',
        'api/soap/*',
        'api/auth/*',
        'editor/articles/*'
    ];
    
    /**
     * Détermine si la requête doit être exemptée de la vérification CSRF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
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
        
        // Vérifier si c'est une requête SOAP basée sur le SOAPAction
        if ($request->header('SOAPAction')) {
            Log::info('VerifyCsrfToken - SOAPAction détecté, CSRF désactivé');
            return true;
        }
        
        return parent::shouldPassThrough($request);
    }
}
