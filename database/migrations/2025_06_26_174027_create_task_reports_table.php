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
        Schema::create('task_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('technicien_id')->constrained('users')->onDelete('cascade');
            
            // Détails des tâches
            $table->string('task_title'); // Titre de la tâche (ex: "Réparation fuite")
            $table->text('task_description'); // Description détaillée
            $table->integer('duration_minutes')->nullable(); // Durée en minutes
            $table->decimal('material_cost', 8, 2)->default(0); // Coût des matériaux
            $table->text('materials_used')->nullable(); // Liste des matériaux utilisés
            $table->enum('difficulty', ['facile', 'normale', 'difficile', 'complexe'])->default('normale');
            
            // Photos avant/après
            $table->json('before_photos')->nullable();
            $table->json('after_photos')->nullable();
            
            // Observations
            $table->text('observations')->nullable(); // Observations du technicien
            $table->text('recommendations')->nullable(); // Recommandations pour le client
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_reports');
    }
};
