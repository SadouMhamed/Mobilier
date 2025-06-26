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
        Schema::create('property_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            $table->datetime('requested_date');
            $table->datetime('confirmed_date')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->timestamps();

            $table->index(['property_id', 'status']);
            $table->index(['client_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_appointments');
    }
};
