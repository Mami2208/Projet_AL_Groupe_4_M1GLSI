<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAPI Info
    |--------------------------------------------------------------------------
    |
    | Informations générales sur l'API qui seront incluses dans la documentation
    |
    */

    'info' => [
        'title' => env('APP_NAME', 'Laravel') . ' API',
        'description' => 'Documentation de l\'API ' . env('APP_NAME', 'Laravel'),
        'version' => '1.0.0',
        'contact' => [
            'name' => 'Support API',
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
    | Serveurs
    |--------------------------------------------------------------------------
    |
    | Liste des serveurs sur lesquels l'API est disponible
    |
    */

    'servers' => [
        [
            'url' => env('APP_URL', 'http://localhost:8000'),
            'description' => 'Serveur ' . env('APP_ENV', 'local') === 'local' ? 'de développement' : 'de production',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Chemins à scanner
    |--------------------------------------------------------------------------
    |
    | Dossiers et fichiers à scanner pour générer la documentation
    |
    */

    'paths' => [
        app_path('Http/Controllers/API'),
        app_path('Http/Controllers/SoapController.php'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sécurité
    |--------------------------------------------------------------------------
    |
    | Configuration de la sécurité de l'API
    |
    */

    'security' => [
        'bearerAuth' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
            'description' => 'Entrez le jeton JWT obtenu lors de l\'authentification',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Schémas
    |--------------------------------------------------------------------------
    |
    | Définition des schémas réutilisables dans la documentation
    |
    */

    'schemas' => [
        'User' => [
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'integer',
                    'format' => 'int64',
                    'example' => 1,
                ],
                'name' => [
                    'type' => 'string',
                    'example' => 'Jean Dupont',
                ],
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                    'example' => 'jean.dupont@example.com',
                ],
                'role' => [
                    'type' => 'string',
                    'enum' => ['visiteur', 'editeur', 'admin'],
                    'example' => 'visiteur',
                ],
                'created_at' => [
                    'type' => 'string',
                    'format' => 'date-time',
                ],
                'updated_at' => [
                    'type' => 'string',
                    'format' => 'date-time',
                ],
            ],
        ],
        'Article' => [
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'integer',
                    'format' => 'int64',
                    'example' => 1,
                ],
                'titre' => [
                    'type' => 'string',
                    'example' => 'Titre de l\'article',
                ],
                'contenu' => [
                    'type' => 'string',
                    'example' => 'Contenu complet de l\'article...',
                ],
                'date_publication' => [
                    'type' => 'string',
                    'format' => 'date-time',
                ],
                'est_publie' => [
                    'type' => 'boolean',
                    'example' => true,
                ],
                'auteur' => [
                    '$ref' => '#/components/schemas/User',
                ],
                'categorie' => [
                    '$ref' => '#/components/schemas/Category',
                ],
                'created_at' => [
                    'type' => 'string',
                    'format' => 'date-time',
                ],
                'updated_at' => [
                    'type' => 'string',
                    'format' => 'date-time',
                ],
            ],
        ],
        'Category' => [
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'integer',
                    'format' => 'int64',
                    'example' => 1,
                ],
                'nom' => [
                    'type' => 'string',
                    'example' => 'Technologie',
                ],
                'slug' => [
                    'type' => 'string',
                    'example' => 'technologie',
                ],
                'description' => [
                    'type' => 'string',
                    'nullable' => true,
                    'example' => 'Articles sur les nouvelles technologies',
                ],
                'created_at' => [
                    'type' => 'string',
                    'format' => 'date-time',
                ],
                'updated_at' => [
                    'type' => 'string',
                    'format' => 'date-time',
                ],
            ],
        ],
        'Error' => [
            'type' => 'object',
            'properties' => [
                'success' => [
                    'type' => 'boolean',
                    'example' => false,
                ],
                'message' => [
                    'type' => 'string',
                    'example' => 'Message d\'erreur détaillé',
                ],
            ],
        ],
        'ValidationError' => [
            'type' => 'object',
            'properties' => [
                'message' => [
                    'type' => 'string',
                    'example' => 'Les données fournies sont invalides.',
                ],
                'errors' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                    ],
                    'example' => [
                        'email' => ['Le champ email est obligatoire.'],
                        'password' => ['Le mot de passe doit contenir au moins 8 caractères.'],
                    ],
                ],
            ],
        ],
        'PaginationLinks' => [
            'type' => 'object',
            'properties' => [
                'first' => [
                    'type' => 'string',
                    'format' => 'url',
                    'nullable' => true,
                    'example' => 'http://example.com/api/articles?page=1',
                ],
                'last' => [
                    'type' => 'string',
                    'format' => 'url',
                    'nullable' => true,
                    'example' => 'http://example.com/api/articles?page=5',
                ],
                'prev' => [
                    'type' => 'string',
                    'format' => 'url',
                    'nullable' => true,
                    'example' => 'http://example.com/api/articles?page=1',
                ],
                'next' => [
                    'type' => 'string',
                    'format' => 'url',
                    'nullable' => true,
                    'example' => 'http://example.com/api/articles?page=3',
                ],
            ],
        ],
        'PaginationMeta' => [
            'type' => 'object',
            'properties' => [
                'current_page' => [
                    'type' => 'integer',
                    'example' => 2,
                ],
                'from' => [
                    'type' => 'integer',
                    'example' => 11,
                ],
                'last_page' => [
                    'type' => 'integer',
                    'example' => 5,
                ],
                'path' => [
                    'type' => 'string',
                    'format' => 'url',
                    'example' => 'http://example.com/api/articles',
                ],
                'per_page' => [
                    'type' => 'integer',
                    'example' => 10,
                ],
                'to' => [
                    'type' => 'integer',
                    'example' => 20,
                ],
                'total' => [
                    'type' => 'integer',
                    'example' => 50,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Réponses par défaut
    |--------------------------------------------------------------------------
    |
    | Réponses HTTP par défaut pour les codes d'état courants
    |
    */

    'default_responses' => [
        '200' => [
            'description' => 'Opération réussie',
        ],
        '201' => [
            'description' => 'Ressource créée avec succès',
        ],
        '204' => [
            'description' => 'Pas de contenu',
        ],
        '400' => [
            'description' => 'Requête incorrecte',
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/Error',
                    ],
                ],
            ],
        ],
        '401' => [
            'description' => 'Non authentifié',
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/Error',
                    ],
                ],
            ],
        ],
        '403' => [
            'description' => 'Accès refusé',
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/Error',
                    ],
                ],
            ],
        ],
        '404' => [
            'description' => 'Ressource non trouvée',
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/Error',
                    ],
                ],
            ],
        ],
        '422' => [
            'description' => 'Erreur de validation',
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/ValidationError',
                    ],
                ],
            ],
        ],
        '500' => [
            'description' => 'Erreur serveur',
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/Error',
                    ],
                ],
            ],
        ],
    ],
];
