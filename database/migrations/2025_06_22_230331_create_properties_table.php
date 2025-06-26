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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['vente', 'location']);
            $table->enum('property_type', ['appartement', 'maison', 'studio', 'bureau', 'terrain', 'local']);
            $table->decimal('price', 15, 2);
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->integer('surface')->nullable(); // en m²
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->boolean('furnished')->default(false);
            $table->json('images')->nullable(); // stockage des chemins d'images
            $table->enum('status', ['en_attente', 'validee', 'rejetee', 'vendue', 'louee'])->default('en_attente');
            $table->text('admin_comment')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
