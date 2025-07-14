<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer les gates d'autorisation
        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('isEditor', function ($user) {
            return $user->role === 'editeur' || $user->role === 'admin';
        });
        
        // Ajouter un macro de réponse SOAP
        Response::macro('soap', function ($data, $status = 200) {
            // Convertir les données en XML SOAP
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">';
            $xml .= '<SOAP-ENV:Body>';
            $xml .= $this->arrayToXml($data);
            $xml .= '</SOAP-ENV:Body>';
            $xml .= '</SOAP-ENV:Envelope>';
            
            return Response::make($xml, $status, [
                'Content-Type' => 'application/soap+xml; charset=utf-8'
            ]);
        });
    }
    
    /**
     * Convertit un tableau en XML
     * 
     * @param array $array
     * @param string|null $rootElement
     * @return string
     */
    private function arrayToXml($array, $rootElement = null)
    {
        $xml = '';
        
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key;
            }
            
            $xml .= '<' . $key . '>';
            
            if (is_array($value)) {
                $xml .= $this->arrayToXml($value);
            } else {
                $xml .= htmlspecialchars($value);
            }
            
            $xml .= '</' . $key . '>';
        }
        
        return $xml;
    }
}
