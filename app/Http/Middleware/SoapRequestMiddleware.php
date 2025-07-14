<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SoapRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si c'est une requête SOAP basée sur le contenu et les en-têtes
        $contentType = $request->header('Content-Type');
        $soapAction = $request->header('SOAPAction');
        $content = $request->getContent();
        
        // Log pour le débogage
        Log::info('SoapRequestMiddleware - Content-Type: ' . $contentType);
        Log::info('SoapRequestMiddleware - SOAPAction: ' . $soapAction);
        
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
        
        // Vérifier si c'est une requête SOAP basée sur le SOAPAction
        if ($soapAction) {
            $isSoapRequest = true;
        }
        
        // Vérifier si c'est une requête SOAP basée sur le contenu XML
        if ($content && (
            str_contains($content, '<soap:Envelope') || 
            str_contains($content, '<SOAP-ENV:Envelope') ||
            str_contains($content, '<soapenv:Envelope')
        )) {
            $isSoapRequest = true;
        }
        
        if ($isSoapRequest) {
            Log::info('SoapRequestMiddleware - Requête SOAP détectée');
            
            // Marquer la requête comme étant une requête SOAP
            $request->attributes->set('is_soap_request', true);
            
            // Désactiver la vérification CSRF pour cette requête
            $request->attributes->set('csrf_disabled', true);
        }
        
        return $next($request);
    }
}
