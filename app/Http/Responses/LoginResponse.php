<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();
        
        // Journalisation pour le débogage
        \Log::info('Redirection après connexion', [
            'user_id' => $user->id,
            'role' => $user->role,
            'intended' => redirect()->intended()->getTargetUrl()
        ]);

        // Redirection en fonction du rôle de l'utilisateur
        if ($user->isAdmin() || $user->isEditeur()) {
            return $request->wantsJson()
                ? new JsonResponse(['redirect' => route('admin.dashboard')], 200)
                : redirect()->intended(route('admin.dashboard'));
        }

        // Redirection par défaut pour les autres rôles
        return $request->wantsJson()
            ? new JsonResponse(['redirect' => url('/dashboard')], 200)
            : redirect()->intended(url('/dashboard'));
    }
}
