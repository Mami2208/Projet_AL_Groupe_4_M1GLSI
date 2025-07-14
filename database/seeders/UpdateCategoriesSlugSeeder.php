<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UpdateCategoriesSlugSeeder extends Seeder
{
    /**
     * Exécuter le seeder.
     *
     * @return void
     */
    public function run()
    {
        $categories = Categorie::all();
        
        foreach ($categories as $categorie) {
            if (empty($categorie->slug)) {
                $categorie->slug = Str::slug($categorie->nom);
                $categorie->save();
            }
        }
        
        $this->command->info('Slugs des catégories mis à jour avec succès !');
    }
}
