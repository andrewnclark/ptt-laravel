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
        Schema::create('crm_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Polymorphic relationship to the subject (what the activity is about)
            $table->morphs('subject');
            
            // Polymorphic relationship to the causer (what caused the activity)
            $table->nullableMorphs('causer');
            
            $table->string('type');  // e.g., 'created', 'updated', 'note_added', 'email_sent'
            $table->string('description');
            $table->json('properties')->nullable(); // Store additional data as JSON
            $table->boolean('is_system_generated')->default(false);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_activities');
    }
}; 