<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titre',
        'slug',
        'contenu',
        'resume',
        'image',
        'categorie_id',
        'utilisateur_id',
        'est_publie',
        'date_publication',
    ];
    
    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement un slug à partir du titre lors de la création
        static::creating(function ($article) {
            $article->slug = Str::slug($article->titre);
        });

        // Mettre à jour le slug si le titre est modifié
        static::updating(function ($article) {
            if ($article->isDirty('titre')) {
                $article->slug = Str::slug($article->titre);
            }
        });
    }
    
    /**
     * Get the route key name for Laravel's route model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Les attributs qui doivent être convertis en types natifs.
     *
     * @var array
     */
    protected $casts = [
        'est_publie' => 'boolean',
        'date_publication' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Les attributs qui doivent être ajoutés au modèle lors de la conversion en tableau/JSON.
     *
     * @var array
     */
    protected $appends = ['extrait'];

    /**
     * Obtenir l'auteur de l'article.
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    /**
     * Obtenir la catégorie de l'article.
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Obtenir les commentaires de l'article.
     */
    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class);
    }

    /**
     * Obtenir un extrait du contenu de l'article.
     *
     * @return string
     */
    public function getExtraitAttribute(): string
    {
        return $this->resume ?: Str::limit(strip_tags($this->contenu), 200);
    }

    /**
     * Obtenir l'URL de l'image de l'article.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Vérifier si l'article est publié.
     *
     * @return bool
     */
    public function estPublie(): bool
    {
        return $this->est_publie && $this->date_publication <= now();
    }

    /**
     * Scope pour les articles publiés.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublie($query)
    {
        return $query->where('est_publie', true)
                    ->where('date_publication', '<=', now());
    }

    /**
     * Scope pour les articles non publiés.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNonPublie($query)
    {
        return $query->where('est_publie', false)
                    ->orWhere('date_publication', '>', now());
    }
}
