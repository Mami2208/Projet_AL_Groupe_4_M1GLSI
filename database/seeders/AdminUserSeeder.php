<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (!Utilisateur::where('email', 'admin@example.com')->exists()) {
            Utilisateur::create([
                'name' => 'Administrateur',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'api_token' => Str::random(80),
            ]);
        }
    }
}
