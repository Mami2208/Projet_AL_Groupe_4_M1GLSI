<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SOAP Server Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the SOAP server.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | SOAP Server Options
    |--------------------------------------------------------------------------
    |
    | Here you can configure the SOAP server options.
    |
    */

    'enabled' => false, // Désactiver temporairement SOAP
    'options' => [
        'uri' => env('SOAP_URI', 'http://localhost:8000/api/soap'),
        'encoding' => 'UTF-8',
        'soap_version' => 2, // Utiliser la valeur numérique directement
        'cache_wsdl' => 0, // Désactiver le cache WSDL
        'trace' => 1,
        'exceptions' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | SOAP Server Class
    |--------------------------------------------------------------------------
    |
    | This is the class that will handle the SOAP requests.
    |
    */

    'handler' => App\Http\Controllers\SoapController::class,

    /*
    |--------------------------------------------------------------------------
    | WSDL Cache
    |--------------------------------------------------------------------------
    |
    | Here you can configure the WSDL cache settings.
    |
    */

    'wsdl_cache' => [
        'enabled' => env('SOAP_WSDL_CACHE_ENABLED', false),
        'path' => storage_path('framework/cache/wsdl'),
        'ttl' => 86400, // 24 hours
    ],

    /*
    |--------------------------------------------------------------------------
    | SOAP Authentication
    |--------------------------------------------------------------------------
    |
    | Here you can configure the SOAP authentication settings.
    |
    */

    'auth' => [
        'enabled' => env('SOAP_AUTH_ENABLED', true),
        'middleware' => ['auth:sanctum'],
    ],

    /*
    |--------------------------------------------------------------------------
    | SOAP Logging
    |--------------------------------------------------------------------------
    |
    | Here you can configure the SOAP logging settings.
    |
    */

    'logging' => [
        'enabled' => env('SOAP_LOGGING_ENABLED', true),
        'channel' => env('SOAP_LOGGING_CHANNEL', 'stack'),
    ],
];
