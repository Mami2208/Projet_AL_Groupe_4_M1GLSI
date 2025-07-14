<?php

namespace App\Http\Controllers\API;

use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API REST - Gestion des articles",
 *     version="1.0.0",
 *     description="API REST pour la gestion des articles d'actualité. Cette API permet de récupérer, créer, mettre à jour et supprimer des articles.",
 *     @OA\Contact(
 *         email="support@example.com",
 *         name="Support API"
 *     )
 * )
 * @OA\Server(
 *     url="/api",
 *     description="Serveur API principal"
 * )
 * @OA\Tag(
 *     name="Articles",
 *     description="Opérations sur les articles"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     in="header",
 *     name="Authorization",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\Schema(
 *     schema="Error",
 *     type="object",
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="Message d'erreur détaillé",
 *         example="La ressource demandée est introuvable."
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         description="Détails des erreurs de validation",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 */
class ArticleApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/articles",
     *     summary="Récupérer la liste des articles publiés",
     *     description="Retourne une liste paginée des articles publiés, triés par date de publication décroissante",
     *     tags={"Articles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de la page à récupérer",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des articles récupérée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Article")
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string"),
     *                 @OA\Property(property="last", type="string"),
     *                 @OA\Property(property="prev", type="string", nullable=true),
     *                 @OA\Property(property="next", type="string", nullable=true)
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $articles = Article::with(['categorie', 'user'])
            ->where('est_publie', true)
            ->latest('date_publication')
            ->paginate($perPage);

        return ArticleResource::collection($articles);
    }

    /**
     * @OA\Get(
     *     path="/articles/categorie/{slug}",
     *     summary="Récupérer les articles d'une catégorie",
     *     description="Retourne la liste des articles publiés appartenant à une catégorie spécifique",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Slug de la catégorie",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="format",
     *         in="query",
     *         description="Format de la réponse (json ou xml)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"json", "xml"}, default="json")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des articles de la catégorie",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Article")
     *         ),
     *         @OA\XmlContent(
     *             @OA\Xml(name="articles")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     )
     * )
     *
     * Récupérer la liste des articles d'une catégorie
     *
     * @param string $slug
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function byCategory($slug, Request $request)
    {
        $perPage = $request->query('per_page', 10);
        
        $articles = Article::with(['categorie', 'user'])
            ->whereHas('categorie', function($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->where('est_publie', true)
            ->latest('date_publication')
            ->paginate($perPage);
            
        return ArticleResource::collection($articles);
    }

    /**
     * @OA\Get(
     *     path="/articles/groupes-par-categorie",
     *     summary="Récupérer les articles groupés par catégorie",
     *     description="Retourne les articles publiés groupés par catégorie",
     *     tags={"Articles"},
     *     @OA\Response(
     *         response=200,
     *         description="Articles groupés par catégorie",
     *         @OA\JsonContent(
     *             type="object",
     *             additionalProperties={
     *                 "type": "array",
     *                 "items": {"$ref": "#/components/schemas/Article"}
     *             }
     *         )
     *     )
     * )
     */
    public function byCategories()
    {
        $categories = Categorie::with(['articles' => function($query) {
            $query->where('est_publie', true)
                  ->latest('date_publication');
        }])->get();

        $groupedArticles = [];
        foreach ($categories as $category) {
            if ($category->articles->isNotEmpty()) {
                $groupedArticles[$category->nom] = $category->articles;
            }
        }

        return response()->json($groupedArticles);
    }
}
