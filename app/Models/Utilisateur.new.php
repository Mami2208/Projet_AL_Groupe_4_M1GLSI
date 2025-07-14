<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone',
        'adresse',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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

    // Relation avec les articles (un utilisateur peut avoir plusieurs articles)
    public function articles()
    {
        return $this->hasMany(Article::class, 'utilisateur_id');
    }

    // Accessor pour l'avatar
    public function getAvatarUrlAttribute()
    {
        if (isset($this->attributes['avatar']) && $this->attributes['avatar']) {
            return asset('storage/' . $this->attributes['avatar']);
        }
        
        // Générer un avatar aléatoire avec l'initiale du nom
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }
}
