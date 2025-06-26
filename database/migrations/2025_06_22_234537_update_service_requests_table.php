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
        Schema::table('service_requests', function (Blueprint $table) {
            // Supprimer les anciens champs
            $table->dropColumn(['preferred_time', 'technicien_comment', 'estimated_price', 'accepted_at']);
            
            // Ajouter les nouveaux champs
            $table->enum('priority', ['basse', 'normale', 'haute', 'urgente'])->default('normale')->after('description');
            $table->string('city')->after('address');
            $table->string('postal_code')->after('city');
            $table->timestamp('started_at')->nullable()->after('assigned_at');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->text('completion_notes')->nullable()->after('completed_at');
            
            // Modifier preferred_date pour être datetime
            $table->datetime('preferred_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Remettre les anciens champs
            $table->time('preferred_time')->nullable();
            $table->text('technicien_comment')->nullable();
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->timestamp('accepted_at')->nullable();
            
            // Supprimer les nouveaux champs
            $table->dropColumn(['priority', 'city', 'postal_code', 'started_at', 'completed_at', 'completion_notes']);
            
            // Remettre preferred_date en date
            $table->date('preferred_date')->nullable()->change();
        });
    }
};
