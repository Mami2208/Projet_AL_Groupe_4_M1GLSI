<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $showPassword = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:8',
    ];

    public function login()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Vérifier si le compte est actif
        if (!Auth::user()->isActive()) {
            Auth::logout();
            
            throw ValidationException::withMessages([
                'email' => __('Votre compte est désactivé. Veuillez contacter l\'administrateur.'),
            ]);
        }

        // Rediriger en fonction du rôle
        $user = Auth::user();
        if ($user->isAdmin() || $user->isEditeur()) {
            return redirect()->intended(route('dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.guest');
    }
}
