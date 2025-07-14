<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Gate;

class Utilisateur extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone',
        'adresse',
        'profile_photo_path',
        'current_team_id',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'api_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Les attributs qui doivent être convertis en types natifs.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
    
    /**
     * Vérifie si l'utilisateur est un administrateur
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    /**
     * Vérifie si l'utilisateur est un éditeur
     */
    public function isEditeur(): bool
    {
        return $this->role === 'editeur' || $this->isAdmin();
    }
    
    /**
     * Vérifie si l'utilisateur est un visiteur simple
     */
    public function isVisiteur(): bool
    {
        return $this->role === 'visiteur';
    }
    
    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     * Compatible avec les vérifications de rôle standards
     */
    public function hasRole($role): bool
    {
        if ($role === 'admin') {
            return $this->isAdmin();
        }
        if ($role === 'editeur') {
            return $this->isEditeur();
        }
        if ($role === 'visiteur') {
            return $this->isVisiteur();
        }
        return $this->role === $role;
    }

    // Relation avec les articles (un utilisateur peut avoir plusieurs articles)
    public function articles()
    {
        return $this->hasMany(Article::class, 'utilisateur_id');
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('utilisateurs');
    }
    
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Enregistrer la politique d'administration
        Gate::define('access-admin', function ($user) {
            return $user->role === 'admin';
        });
    }

    /**
     * Les attributs qui déterminent l'URL de la photo de profil.
     *
     * @return string
     */
    public function profilePhotoUrl(): string
    {
        if ($this->profile_photo_path) {
            return $this->profile_photo_path;
        }
        
        // Générer un avatar aléatoire avec l'initiale du nom
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }
    
    /**
     * Obtenir l'URL de la photo de profil de l'utilisateur.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profilePhotoUrl();
    }
}
