<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation de l'API - {{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    
    <!-- Swagger UI CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.0.0/swagger-ui.css" />
    
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        
        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }
        
        body {
            margin: 0;
            background: #fafafa;
        }
        
        .topbar {
            background-color: #1b1b1b;
            padding: 10px 0;
            text-align: center;
            color: white;
            font-family: sans-serif;
        }
        
        .topbar h1 {
            margin: 0;
            font-size: 1.5em;
        }
        
        .topbar a {
            color: #61dafb;
            text-decoration: none;
        }
        
        .topbar a:hover {
            text-decoration: underline;
        }
        
        .info {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            font-family: sans-serif;
            line-height: 1.6;
        }
        
        .info h2 {
            color: #333;
            margin-top: 30px;
        }
        
        .info p {
            color: #555;
        }
        
        .info code {
            background: #f4f4f4;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
        }
        
        .auth-setup {
            background: #e7f5ff;
            border-left: 4px solid #4dabf7;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        
        .auth-setup h3 {
            margin-top: 0;
            color: #1c7ed6;
        }
        
        .endpoint {
            background: white;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .endpoint h3 {
            margin-top: 0;
            color: #24292e;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
        }
        
        .endpoint pre {
            background: #f6f8fa;
            padding: 10px;
            border-radius: 3px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>Documentation de l'API - {{ config('app.name') }}</h1>
        <p>Documentation interactive de l'API REST et SOAP</p>
    </div>
    
    <div id="swagger-ui"></div>
    
    <div class="info">
        <h2>Introduction</h2>
        <p>Bienvenue dans la documentation de l'API {{ config('app.name') }}. Cette API vous permet d'accéder aux fonctionnalités de notre plateforme de manière programmatique.</p>
        
        <h2>Authentification</h2>
        <p>L'API utilise l'authentification par jeton Bearer. Pour vous authentifier, incluez le jeton dans l'en-tête <code>Authorization</code> de vos requêtes :</p>
        
        <pre>Authorization: Bearer {votre_jeton}</pre>
        
        <div class="auth-setup">
            <h3>Comment obtenir un jeton ?</h3>
            <ol>
                <li>Connectez-vous à votre compte administrateur</li>
                <li>Allez dans la section "Jetons API"</li>
                <li>Générez un nouveau jeton</li>
                <li>Copiez le jeton et conservez-le en lieu sûr (il ne sera affiché qu'une seule fois)</li>
            </ol>
        </div>
        
        <h2>Formats de réponse</h2>
        <p>L'API supporte deux formats de réponse :</p>
        <ul>
            <li><strong>JSON</strong> (par défaut) - <code>Accept: application/json</code></li>
            <li><strong>XML</strong> - <code>Accept: application/xml</code> ou <code>?format=xml</code></li>
        </ul>
        
        <h2>Codes de statut HTTP</h2>
        <p>L'API utilise les codes de statut HTTP standards :</p>
        <ul>
            <li><strong>200 OK</strong> - Requête réussie</li>
            <li><strong>201 Created</strong> - Ressource créée avec succès</li>
            <li><strong>400 Bad Request</strong> - Requête mal formée</li>
            <li><strong>401 Unauthorized</strong> - Authentification requise</li>
            <li><strong>403 Forbidden</strong> - Accès refusé</li>
            <li><strong>404 Not Found</strong> - Ressource non trouvée</li>
            <li><strong>422 Unprocessable Entity</strong> - Erreur de validation</li>
            <li><strong>500 Internal Server Error</strong> - Erreur serveur</li>
        </ul>
        
        <h2>Points de terminaison SOAP</h2>
        <p>En plus de l'API REST, nous fournissons également une API SOAP pour certaines fonctionnalités avancées :</p>
        
        <div class="endpoint">
            <h3>Authentification SOAP</h3>
            <p>URL du service : <code>{{ url('/api/soap') }}</code></p>
            <p>Méthode : <code>authenticate</code></p>
            <p>Paramètres :</p>
            <ul>
                <li><code>email</code> (string, requis) - Email de l'utilisateur</li>
                <li><code>password</code> (string, requis) - Mot de passe de l'utilisateur</li>
            </ul>
            <p>Réponse :</p>
            <pre>{
  "success": true,
  "token": "1|abcdefghijklmnopqrstuvwxyz",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "role": "admin"
  }
}</pre>
        </div>
        
        <div class="endpoint">
            <h3>Liste des utilisateurs (admin uniquement)</h3>
            <p>URL du service : <code>{{ url('/api/soap') }}</code></p>
            <p>Méthode : <code>listUsers</code></p>
            <p>Paramètres : Aucun</p>
            <p>Authentification requise : Oui (jeton d'administration)</p>
            <p>Réponse :</p>
            <pre>{
  "success": true,
  "users": [
    {
      "id": 1,
      "name": "Admin",
      "email": "admin@example.com",
      "role": "admin",
      "created_at": "2023-01-01T00:00:00.000000Z"
    },
    ...
  ]
}</pre>
        </div>
        
        <h2>Support</h2>
        <p>Si vous avez des questions ou rencontrez des problèmes, veuillez contacter notre équipe de support à <a href="mailto:support@example.com">support@example.com</a>.</p>
    </div>
    
    <!-- Scripts pour Swagger UI -->
    <script src="https://unpkg.com/swagger-ui-dist@5.0.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.0.0/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            // Configuration de Swagger UI
            const ui = SwaggerUIBundle({
                url: "{{ url('/api/documentation/json') }}",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                requestInterceptor: (req) => {
                    // Ajouter le jeton d'authentification si disponible
                    const token = localStorage.getItem('api_token');
                    if (token) {
                        req.headers['Authorization'] = 'Bearer ' + token;
                    }
                    return req;
                }
            });
            
            // Gestion de l'authentification
            window.ui = ui;
            
            // Fonction pour se connecter et obtenir un jeton
            window.login = function() {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                fetch('{{ route("api.auth.login") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        localStorage.setItem('api_token', data.token);
                        alert('Connexion réussie !');
                        window.location.reload();
                    } else {
                        alert('Erreur d\'authentification : ' + (data.message || 'Identifiants invalides'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la connexion');
                });
            };
            
            // Fonction pour se déconnecter
            window.logout = function() {
                localStorage.removeItem('api_token');
                window.location.reload();
            };
        };
    </script>
</body>
</html>
