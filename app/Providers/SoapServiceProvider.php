<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class SoapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Enregistrer la macro de réponse SOAP
        Response::macro('soap', function ($data, $status = 200) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">' . "\n";
            $xml .= '  <soap:Body>' . "\n";
            
            // Fonction pour convertir un tableau en XML
            $arrayToXml = function($data, $indent = '') use (&$arrayToXml) {
                $xml = '';
                
                foreach ($data as $key => $value) {
                    if (is_array($value) && !empty($value) && array_keys($value) !== range(0, count($value) - 1)) {
                        // Tableau associatif
                        $xml .= "$indent<$key>\n";
                        $xml .= $arrayToXml($value, $indent . '  ');
                        $xml .= "$indent</$key>\n";
                    } elseif (is_array($value)) {
                        // Tableau indexé
                        foreach ($value as $item) {
                            $xml .= "$indent<$key>\n";
                            if (is_array($item)) {
                                $xml .= $arrayToXml($item, $indent . '  ');
                            } else {
                                $xml .= $indent . '  ' . htmlspecialchars($item) . "\n";
                            }
                            $xml .= "$indent</$key>\n";
                        }
                    } else {
                        // Valeur simple
                        $xml .= "$indent<$key>" . htmlspecialchars($value) . "</$key>\n";
                    }
                }
                
                return $xml;
            };
            
            // Si c'est une réponse d'erreur
            if (isset($data['success']) && !$data['success']) {
                $xml .= '    <soap:Fault>' . "\n";
                $xml .= '      <faultcode>SOAP-ENV:Server</faultcode>' . "\n";
                $xml .= '      <faultstring>' . htmlspecialchars($data['message'] ?? 'Erreur inconnue') . '</faultstring>' . "\n";
                $xml .= '    </soap:Fault>' . "\n";
            } else {
                // Convertir le tableau en XML
                $xml .= '    <ns1:Response xmlns:ns1="http://localhost/soap">' . "\n";
                $xml .= $arrayToXml($data, '      ');
                $xml .= '    </ns1:Response>' . "\n";
            }
            
            $xml .= '  </soap:Body>' . "\n";
            $xml .= '</soap:Envelope>';
            
            return response($xml, $status)->header('Content-Type', 'text/xml; charset=utf-8');
        });
    }
    

}
