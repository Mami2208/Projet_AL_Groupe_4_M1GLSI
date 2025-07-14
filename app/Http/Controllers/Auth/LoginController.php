<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Mettre à jour la date de dernière connexion
        $user->last_login_at = now();
        $user->save();
        
        // Journalisation pour le débogage
        Log::info('Utilisateur connecté', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ]);
        
        // Redirection en fonction du rôle
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'editeur') {
            return redirect()->route('editor.dashboard');
        }
        
        // Par défaut, rediriger vers la page d'accueil
        return redirect('/');
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
    
    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function loggedOut(Request $request)
    {
        Log::info('Utilisateur déconnecté');
    }
    
    /**
     * Affiche le formulaire de confirmation de mot de passe.
     *
     * @return \Illuminate\View\View
     */
    public function showConfirmPasswordForm()
    {
        return view('auth.confirm-password');
    }
    
    /**
     * Confirme le mot de passe de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmPassword(Request $request)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => __('Le mot de passe fourni ne correspond pas à nos enregistrements.'),
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    }
    
    /**
     * Vérifie l'email de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->markEmailAsVerified();

        return redirect($this->redirectPath())->with('verified', true);
    }
    
    /**
     * Renvoie un nouvel email de vérification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                        ? new JsonResponse([], 204)
                        : redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return $request->wantsJson()
                    ? new JsonResponse([], 202)
                    : back()->with('status', 'verification-link-sent');
    }
}
