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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('name');
            $table->integer('years_of_experience');
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->enum('skill_level', ['beginner', 'intermediate', 'advanced', 'expert']);
            $table->string('certificate')->nullable();
            $table->json('sample_pictures')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
