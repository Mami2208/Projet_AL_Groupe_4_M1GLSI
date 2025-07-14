<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use OpenApi\Generator;
use Symfony\Component\Yaml\Yaml;

class GenerateApiDocumentation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API documentation from annotations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Génération de la documentation de l\'API...');
        
        // Vérifier si le répertoire de destination existe
        $outputDir = public_path('api-docs');
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }
        
        // Générer la documentation OpenAPI
        $openapi = Generator::scan([
            app_path('Http/Controllers/API'),
            app_path('Http/Controllers/SoapController.php'),
        ]);
        
        // Convertir en tableau pour manipulation
        $openapiArray = json_decode($openapi->toJson(), true);
        
        // Ajouter des informations supplémentaires depuis la configuration
        $openapiArray = array_merge($openapiArray, [
            'info' => [
                'title' => config('swagger.api.title'),
                'version' => config('swagger.api.version'),
                'description' => config('swagger.api.description'),
                'contact' => [
                    'name' => config('swagger.api.contact.name'),
                    'email' => config('swagger.api.contact.email'),
                    'url' => config('swagger.api.contact.url'),
                ],
                'license' => [
                    'name' => config('swagger.api.license.name'),
                    'url' => config('swagger.api.license.url'),
                ],
            ],
            'servers' => array_map(function ($server) {
                return [
                    'url' => $server['url'],
                    'description' => $server['description'],
                    'variables' => $server['variables'] ?? [],
                ];
            }, config('swagger.servers')),
            'components' => [
                'securitySchemes' => config('swagger.security_schemes'),
            ],
        ]);
        
        // Sauvegarder en JSON
        $jsonPath = $outputDir . '/openapi.json';
        file_put_contents($jsonPath, json_encode($openapiArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        // Sauvegarder en YAML
        $yamlPath = $outputDir . '/openapi.yaml';
        file_put_contents($yamlPath, Yaml::dump($openapiArray, 10, 2, Yaml::DUMP_OBJECT_AS_MAP));
        
        $this->info("Documentation générée avec succès dans :");
        $this->line("- JSON: " . $jsonPath);
        $this->line("- YAML: " . $yamlPath);
        
        return 0;
    }
}
