<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="titre", type="string", example="Titre de l'article"),
 *     @OA\Property(property="contenu", type="string", example="Contenu de l'article"),
 *     @OA\Property(property="categorie_id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="auteur",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Nom de l'auteur")
 *     ),
 *     @OA\Property(
 *         property="categorie",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="nom", type="string", example="Nom de la catégorie")
 *     )
 * )
 */
class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'contenu' => $this->contenu,
            'categorie_id' => $this->categorie_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'auteur' => [
                'id' => $this->user->id,
                'name' => $this->user->name
            ],
            'categorie' => [
                'id' => $this->categorie->id,
                'nom' => $this->categorie->nom
            ]
        ];
    }
}
