<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateNewAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin2@example.com';
        
        if (!User::where('email', $email)->exists()) {
            $user = User::create([
                'name' => 'Admin Secondaire',
                'email' => $email,
                'password' => Hash::make('password'), // À changer après la première connexion
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            $this->command->info("Administrateur créé avec succès !");
            $this->command->info("Email: " . $email);
            $this->command->info("Mot de passe temporaire: password");
            $this->command->warn("N'oubliez pas de changer le mot de passe après la première connexion !");
        } else {
            $this->command->warn("Un administrateur avec l'email {$email} existe déjà.");
        }
    }
}
