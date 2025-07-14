<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle User de compatibilité
 * 
 * Ce modèle est fourni pour assurer la compatibilité avec les packages
 * qui s'attendent à trouver une classe User.
 * Il étend la classe Utilisateur qui contient la logique métier.
 */
class User extends Utilisateur
{
    protected $table = 'utilisateurs';
    
    // Toutes les autres fonctionnalités sont héritées de la classe Utilisateur
}
