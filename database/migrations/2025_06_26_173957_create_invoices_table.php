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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Numéro de facture (ex: FAC-2025-001)
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Admin qui a créé la facture
            
            // Détails de la facture
            $table->decimal('base_amount', 10, 2); // Montant de base du service
            $table->decimal('additional_amount', 10, 2)->default(0); // Montant supplémentaire pour tâches extras
            $table->decimal('discount_amount', 10, 2)->default(0); // Remise
            $table->decimal('total_amount', 10, 2); // Montant total
            $table->text('admin_notes')->nullable(); // Notes de l'admin
            
            // Statuts
            $table->enum('status', ['draft', 'sent', 'viewed', 'paid', 'cancelled'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('due_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
