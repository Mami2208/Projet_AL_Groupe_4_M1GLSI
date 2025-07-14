<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Categorie extends Model
{
    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'description',
        'slug',
        'couleur',
    ];

    /**
     * Obtenir les articles de cette catégorie.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Obtenir le nombre d'articles publiés dans cette catégorie.
     *
     * @return int
     */
    public function getNombreArticlesPubliesAttribute(): int
    {
        return $this->articles()
            ->where('est_publie', true)
            ->where('date_publication', '<=', now())
            ->count();
    }

    /**
     * Obtenir l'URL de la catégorie.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('categorie.show', $this->slug);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($categorie) {
            $categorie->slug = Str::slug($categorie->nom);
        });

        static::updating(function ($categorie) {
            if ($categorie->isDirty('nom')) {
                $categorie->slug = Str::slug($categorie->nom);
            }
        });
    }

    /**
     * Scope pour les catégories avec des articles publiés.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvecArticlesPublies($query)
    {
        return $query->whereHas('articles', function($q) {
            $q->where('est_publie', true)
              ->where('date_publication', '<=', now());
        });
    }
}
