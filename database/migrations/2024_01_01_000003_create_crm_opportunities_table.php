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
        Schema::create('crm_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('crm_companies')->onDelete('cascade');
            $table->foreignId('contact_id')->nullable()->constrained('crm_contacts')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('value', 10, 2)->default(0);
            $table->enum('status', ['new', 'qualified', 'proposal', 'negotiation', 'won', 'lost'])->default('new');
            $table->enum('source', ['website', 'referral', 'cold_call', 'event', 'social_media', 'other'])->default('website');
            $table->date('expected_close_date')->nullable();
            $table->date('actual_close_date')->nullable();
            $table->integer('probability')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index('expected_close_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_opportunities');
    }
}; 