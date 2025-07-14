<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Vérifie si l'utilisateur est administrateur
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function accessAdminPanel(User $user)
    {
        return $user->role === 'admin';
    }
}
