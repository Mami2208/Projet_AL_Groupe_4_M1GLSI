<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SoapController;

class SoapServerController extends Controller
{
    protected $soapController;
    
    public function __construct(SoapController $soapController)
    {
        $this->soapController = $soapController;
        
        // Désactiver le WSDL pour éviter les erreurs de cache
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
    }
    
    /**
     * Affiche le WSDL du service SOAP
     *
     * @return \Illuminate\Http\Response
     */
    public function wsdl()
    {
        $wsdlPath = app_path('Services/UserService.wsdl');
        
        if (!file_exists($wsdlPath)) {
            abort(404, 'WSDL file not found');
        }
        
        return response(file_get_contents($wsdlPath), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
    
    /**
     * Gère les requêtes SOAP
     *
     * @return \Illuminate\Http\Response
     */
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
}
