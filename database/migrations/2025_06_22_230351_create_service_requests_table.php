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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('technicien_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('description');
            $table->string('address');
            $table->string('phone');
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            $table->enum('status', ['en_attente', 'assignee', 'acceptee', 'refusee', 'en_cours', 'terminee'])->default('en_attente');
            $table->text('technicien_comment')->nullable();
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
