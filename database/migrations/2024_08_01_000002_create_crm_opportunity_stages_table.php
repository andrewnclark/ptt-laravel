<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crm_opportunity_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique(); // Unique identifier for the stage
            $table->text('description')->nullable();
            $table->integer('position')->default(0); // For ordering the stages
            $table->decimal('probability', 5, 2)->default(0); // Win probability percentage
            $table->string('color')->default('#000000'); // Display color for the stage
            $table->boolean('is_active')->default(true);
            $table->boolean('is_won_stage')->default(false); // Whether this stage represents a won opportunity
            $table->boolean('is_lost_stage')->default(false); // Whether this stage represents a lost opportunity
            $table->timestamps();
            
            // Indexes
            $table->index('position');
            $table->index(['is_active', 'position']);
        });
        
        // Add stage_id column to opportunities table
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->foreignId('stage_id')->nullable()->after('status')
                  ->constrained('crm_opportunity_stages')
                  ->nullOnDelete();
            
            $table->index('stage_id');
        });
        
        // Insert default stages
        DB::table('crm_opportunity_stages')->insert([
            [
                'name' => 'New Lead',
                'key' => 'new',
                'description' => 'Initial contact with potential client',
                'position' => 10,
                'probability' => 10.00,
                'color' => '#3B82F6', // blue
                'is_active' => true,
                'is_won_stage' => false,
                'is_lost_stage' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Qualified',
                'key' => 'qualified',
                'description' => 'Lead has been qualified as a potential opportunity',
                'position' => 20,
                'probability' => 25.00,
                'color' => '#10B981', // green
                'is_active' => true,
                'is_won_stage' => false,
                'is_lost_stage' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Proposal',
                'key' => 'proposal',
                'description' => 'Proposal has been sent to the client',
                'position' => 30,
                'probability' => 50.00,
                'color' => '#FBBF24', // yellow
                'is_active' => true,
                'is_won_stage' => false,
                'is_lost_stage' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Negotiation',
                'key' => 'negotiation',
                'description' => 'Negotiating terms with the client',
                'position' => 40,
                'probability' => 75.00,
                'color' => '#F97316', // orange
                'is_active' => true,
                'is_won_stage' => false,
                'is_lost_stage' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Won',
                'key' => 'won',
                'description' => 'Deal has been closed and won',
                'position' => 50,
                'probability' => 100.00,
                'color' => '#22C55E', // green
                'is_active' => true,
                'is_won_stage' => true,
                'is_lost_stage' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Lost',
                'key' => 'lost',
                'description' => 'Deal has been closed and lost',
                'position' => 60,
                'probability' => 0.00,
                'color' => '#EF4444', // red
                'is_active' => true,
                'is_won_stage' => false,
                'is_lost_stage' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_opportunities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('stage_id');
        });
        
        Schema::dropIfExists('crm_opportunity_stages');
    }
}; 