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
        Schema::table('appointments', function (Blueprint $table) {
            // Supprimer l'ancienne contrainte de clé étrangère vers properties
            $table->dropForeign(['property_id']);
            $table->dropColumn('property_id');
            
            // Supprimer l'ancien champ message
            $table->dropColumn('message');
            
            // Ajouter la clé étrangère vers service_requests
            $table->foreignId('service_request_id')->after('client_id')->constrained()->onDelete('cascade');
            
            // Renommer appointment_date en scheduled_at
            $table->renameColumn('appointment_date', 'scheduled_at');
            
            // Ajouter les nouveaux champs
            $table->integer('duration')->after('scheduled_at')->comment('Durée en minutes');
            $table->text('notes')->nullable()->after('duration');
            $table->timestamp('completed_at')->nullable()->after('notes');
            $table->text('cancellation_reason')->nullable()->after('completed_at');
            
            // Mettre à jour les statuts
            $table->dropColumn('status');
            $table->enum('status', ['planifie', 'confirme', 'termine', 'annule'])->default('planifie')->after('cancellation_reason');
            
            // Supprimer admin_notes
            $table->dropColumn('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Remettre l'ancien système
            $table->dropForeign(['service_request_id']);
            $table->dropColumn(['service_request_id', 'duration', 'notes', 'completed_at', 'cancellation_reason']);
            
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->text('admin_notes')->nullable();
            
            $table->renameColumn('scheduled_at', 'appointment_date');
            
            $table->dropColumn('status');
            $table->enum('status', ['demandee', 'planifiee', 'confirmee', 'annulee', 'terminee'])->default('demandee');
        });
    }
};
