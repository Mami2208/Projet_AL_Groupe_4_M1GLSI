<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Schema as DoctrineSchema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajouter le champ slug s'il n'existe pas déjà
        if (!Schema::hasColumn('articles', 'slug')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('titre');
            });
            
            // Mettre à jour les slugs existants
            $articles = \App\Models\Article::all();
            foreach ($articles as $article) {
                $article->slug = \Illuminate\Support\Str::slug($article->titre);
                $article->save();
            }
            
            // Rendre la colonne unique après la mise à jour
            Schema::table('articles', function (Blueprint $table) {
                $table->string('slug')->unique()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer le champ slug s'il existe
        if (Schema::hasColumn('articles', 'slug')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
