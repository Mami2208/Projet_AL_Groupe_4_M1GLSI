<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Documentation Info
    |--------------------------------------------------------------------------
    |
    | This section contains the information that will be used in the OpenAPI
    | documentation. You should update these values to match your application.
    |
    */
    
    'api' => [
        'version' => env('API_VERSION', '1.0.0'),
        'title' => env('APP_NAME', 'Laravel') . ' API Documentation',
        'description' => 'Documentation for the ' . env('APP_NAME', 'Laravel') . ' REST API',
        'contact' => [
            'name' => 'API Support',
            'email' => 'support@example.com',
            'url' => 'https://example.com/support',
        ],
        'license' => [
            'name' => 'MIT',
            'url' => 'https://opensource.org/licenses/MIT',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | API Server Configuration
    |--------------------------------------------------------------------------
    |
    | Define the servers where your API is accessible. You can add multiple
    | server configurations for different environments.
    |
    */
    
    'servers' => [
        [
            'url' => env('APP_URL', 'http://localhost:8000') . '/api',
            'description' => env('APP_ENV', 'local') === 'local' ? 'Local Server' : 'Production Server',
            'variables' => [
                'basePath' => [
                    'default' => '/api',
                    'description' => 'API base path',
                ],
            ],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Security Schemes
    |--------------------------------------------------------------------------
    |
    | Define the security schemes used by your API, such as API keys, OAuth2,
    | or HTTP authentication.
    |
    */
    
    'security_schemes' => [
        'bearerAuth' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
            'description' => 'Enter your bearer token in the format: Bearer {token}',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Scan Options
    |--------------------------------------------------------------------------
    |
    | Configure the directories that should be scanned for API documentation.
    | You can also exclude specific directories from the scan.
    |
    */
    
    'scan' => [
        'paths' => [
            app_path('Http/Controllers/API'),
            app_path('Http/Controllers/SoapController.php'),
        ],
        'exclude' => [
            // Add paths to exclude from scanning
        ],
        'storage' => storage_path('api-docs'),
        'pattern' => '*.php',
        'openapi' => '3.0.0',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | UI Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the Swagger UI interface.
    |
    */
    
    'ui' => [
        'enabled' => env('SWAGGER_UI_ENABLED', true),
        'url' => '/api/documentation',
        'assets_path' => 'vendor/swagger-api/swagger-ui/dist',
        'layout' => 'StandaloneLayout',
        'display' => [
            'doc_expansion' => 'none',
            'filter' => true,
            'deep_linking' => true,
            'show_extensions' => true,
            'show_common_extensions' => true,
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Default Responses
    |--------------------------------------------------------------------------
    |
    | Define default responses that will be applied to all API endpoints.
    |
    */
    
    'default_responses' => [
        '200' => [
            'description' => 'Successful operation',
        ],
        '401' => [
            'description' => 'Unauthenticated',
        ],
        '403' => [
            'description' => 'Forbidden',
        ],
        '404' => [
            'description' => 'Not Found',
        ],
        '422' => [
            'description' => 'Validation Error',
            'content' => [
                'application/json' => [
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'message' => [
                                'type' => 'string',
                                'example' => 'The given data was invalid.',
                            ],
                            'errors' => [
                                'type' => 'object',
                                'additionalProperties' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'string',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        '500' => [
            'description' => 'Server Error',
        ],
    ],
];
