<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DisableCsrfForSoap
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
        // Désactiver complètement la vérification CSRF pour les requêtes SOAP
        if (str_starts_with($request->path(), 'soap')) {
            Log::info('DisableCsrfForSoap: CSRF désactivé pour la requête SOAP: ' . $request->path());
            $request->attributes->set('csrf_disabled', true);
        }

        return $next($request);
    }
}
