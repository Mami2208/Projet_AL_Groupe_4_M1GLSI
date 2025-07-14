<?php

namespace App\Console\Commands;

use App\Models\Utilisateur;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('Entrez le nom de l\'administrateur');
        $email = $this->ask('Entrez l\'email de l\'administrateur');
        $password = $this->secret('Entrez le mot de passe');
        $passwordConfirm = $this->secret('Confirmez le mot de passe');

        // Vérification des mots de passe
        if ($password !== $passwordConfirm) {
            $this->error('Les mots de passe ne correspondent pas !');
            return 1;
        }

        // Vérification si l'email existe déjà
        if (Utilisateur::where('email', $email)->exists()) {
            $this->error('Un utilisateur avec cet email existe déjà !');
            return 1;
        }

        try {
            // Création de l'utilisateur admin
            $user = Utilisateur::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'remember_token' => Str::random(10),
            ]);

            $this->info('\nAdministrateur créé avec succès !');
            $this->info('Email: ' . $user->email);
            $this->info('Rôle: ' . $user->role);
            $this->info('\nVous pouvez maintenant vous connecter avec ces identifiants.');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Erreur lors de la création de l\'administrateur: ' . $e->getMessage());
            return 1;
        }
    }
}
