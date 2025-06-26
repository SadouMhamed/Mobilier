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
        // Add new fields to service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('completion_notes');
            $table->text('technicien_notes')->nullable()->after('admin_notes');
            $table->text('final_notes')->nullable()->after('technicien_notes');
            $table->boolean('is_archived')->default(false)->after('final_notes');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
            $table->tinyInteger('client_rating')->nullable()->after('archived_at'); // 1-5 étoiles
            $table->text('client_feedback')->nullable()->after('client_rating'); // Commentaire client
        });

        // Add new fields to appointments table
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('proposed_by')->nullable()->after('cancellation_reason'); // 'technicien' or 'admin'
            $table->timestamp('proposed_date')->nullable()->after('proposed_by');
            $table->text('proposed_reason')->nullable()->after('proposed_date');
            $table->boolean('is_locked')->default(false)->after('proposed_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['admin_notes', 'technicien_notes', 'final_notes', 'is_archived', 'archived_at', 'client_rating', 'client_feedback']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['proposed_by', 'proposed_date', 'proposed_reason', 'is_locked']);
        });
    }
};
