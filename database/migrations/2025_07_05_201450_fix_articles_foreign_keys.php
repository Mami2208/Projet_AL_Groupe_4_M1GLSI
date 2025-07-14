<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cette migration n'est plus nécessaire car la contrainte a été gérée dans la migration précédente
        // La méthode est laissée vide pour éviter les erreurs
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cette migration n'est plus nécessaire car la contrainte a été gérée dans la migration précédente
        // La méthode est laissée vide pour éviter les erreurs
    }
};
