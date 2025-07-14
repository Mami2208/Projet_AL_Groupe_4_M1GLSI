<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les utilisateurs avec le rôle 'visiteur' vers 'editeur'
        \DB::table('utilisateurs')
            ->where('role', 'visiteur')
            ->update(['role' => 'editeur']);
            
        // Pour SQLite, nous allons créer une nouvelle table, copier les données,
        // supprimer l'ancienne table et renommer la nouvelle
        if (\DB::getDriverName() === 'sqlite') {
            // Créer une nouvelle table avec la structure mise à jour
            \Schema::create('utilisateurs_new', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['editeur', 'admin'])->default('editeur');
                $table->string('api_token', 80)->unique()->nullable()->default(null);
                $table->rememberToken();
                $table->timestamp('last_login_at')->nullable();
                $table->text('profile_photo_path')->nullable();
                $table->text('two_factor_secret')->nullable();
                $table->text('two_factor_recovery_codes')->nullable();
                $table->timestamp('two_factor_confirmed_at')->nullable();
                $table->string('current_team_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            
            // Copier les données de l'ancienne table vers la nouvelle
            \DB::table('utilisateurs_new')->insert(
                \DB::table('utilisateurs')->get()->map(function($user) {
                    return (array) $user;
                })->toArray()
            );
            
            // Supprimer l'ancienne table et renommer la nouvelle
            \Schema::drop('utilisateurs');
            \Schema::rename('utilisateurs_new', 'utilisateurs');
        } else {
            // Pour les autres bases de données, utiliser la syntaxe ALTER TABLE standard
            \DB::statement("ALTER TABLE utilisateurs 
                MODIFY COLUMN role ENUM('editeur', 'admin') 
                NOT NULL 
                DEFAULT 'editeur'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pour la méthode down, nous allons également gérer SQLite différemment
        if (\DB::getDriverName() === 'sqlite') {
            // Créer une nouvelle table avec la structure d'origine
            \Schema::create('utilisateurs_old', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['visiteur', 'editeur', 'admin'])->default('visiteur');
                $table->string('api_token', 80)->unique()->nullable()->default(null);
                $table->rememberToken();
                $table->timestamp('last_login_at')->nullable();
                $table->text('profile_photo_path')->nullable();
                $table->text('two_factor_secret')->nullable();
                $table->text('two_factor_recovery_codes')->nullable();
                $table->timestamp('two_factor_confirmed_at')->nullable();
                $table->string('current_team_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            
            // Copier les données de la table actuelle vers la nouvelle
            \DB::table('utilisateurs_old')->insert(
                \DB::table('utilisateurs')->get()->map(function($user) {
                    return (array) $user;
                })->toArray()
            );
            
            // Supprimer l'ancienne table et renommer la nouvelle
            \Schema::drop('utilisateurs');
            \Schema::rename('utilisateurs_old', 'utilisateurs');
        } else {
            // Pour les autres bases de données, utiliser la syntaxe ALTER TABLE standard
            \DB::statement("ALTER TABLE utilisateurs 
                MODIFY COLUMN role ENUM('visiteur', 'editeur', 'admin') 
                NOT NULL 
                DEFAULT 'visiteur'");
        }
    }
};
