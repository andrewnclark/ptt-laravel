<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('status')->default('not_started');
            $table->string('priority')->default('medium');
            $table->foreignId('company_id')->nullable()->constrained('crm_companies')->onDelete('cascade');
            $table->foreignId('contact_id')->nullable()->constrained('crm_contacts')->onDelete('cascade');
            $table->foreignId('opportunity_id')->nullable()->constrained('crm_opportunities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_tasks');
    }
}; 