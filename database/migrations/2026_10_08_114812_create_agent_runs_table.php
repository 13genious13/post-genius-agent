<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_runs', function (Blueprint $table) {
            $table->id();




            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->foreignId('campaign_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();




            $table->foreignId('marketing_goal_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();




            $table->foreignId('research_run_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();




            $table->foreignId('campaign_strategy_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();




            $table->foreignId('started_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();




            $table->uuid('uuid')->unique();




            $table->string('run_type', 50)
                ->default('campaign');




            $table->string('status', 40)
                ->default('pending');




            $table->string('current_agent', 100)
                ->nullable();




            $table->string('current_stage', 100)
                ->nullable();




            $table->text('objective');




            $table->string('autonomy_mode', 30)
                ->default('assisted');




            $table->unsignedInteger('progress')
                ->default(0);




            $table->json('context')
                ->nullable();




            $table->json('result')
                ->nullable();




            $table->json('summary')
                ->nullable();




            $table->json('metrics')
                ->nullable();




            $table->text('error_message')
                ->nullable();




            $table->timestamp('started_at')
                ->nullable();




            $table->timestamp('completed_at')
                ->nullable();




            $table->timestamps();




            $table->index([
                'business_id',
                'status',
            ]);




            $table->index([
                'campaign_id',
                'status',
            ]);




            $table->index([
                'status',
                'created_at',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('agent_runs');
    }
};
