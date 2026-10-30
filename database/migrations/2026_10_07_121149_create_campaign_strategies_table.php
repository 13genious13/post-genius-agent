<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_strategies', function (Blueprint $table) {
            $table->id();




            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->foreignId('campaign_id')
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




            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();




            $table->unsignedInteger('version')
                ->default(1);




            $table->string('status')
                ->default('pending');




            $table->string('strategy_type')
                ->default('campaign');




            $table->text('objective');




            $table->text('executive_summary')
                ->nullable();




            $table->text('strategic_thesis')
                ->nullable();




            $table->json('positioning')
                ->nullable();




            $table->json('audience_strategy')
                ->nullable();




            $table->json('messaging_framework')
                ->nullable();




            $table->json('content_pillars')
                ->nullable();




            $table->json('channel_strategy')
                ->nullable();




            $table->json('funnel_strategy')
                ->nullable();




            $table->json('campaign_phases')
                ->nullable();




            $table->json('kpi_framework')
                ->nullable();




            $table->json('experiments')
                ->nullable();




            $table->json('risks')
                ->nullable();




            $table->json('assumptions')
                ->nullable();




            $table->json('agent_explanation')
                ->nullable();




            $table->json('raw_strategy')
                ->nullable();




            $table->string('ai_provider')
                ->nullable();




            $table->string('ai_model')
                ->nullable();




            $table->unsignedBigInteger('prompt_tokens')
                ->nullable();




            $table->unsignedBigInteger('completion_tokens')
                ->nullable();




            $table->unsignedBigInteger('total_tokens')
                ->nullable();




            $table->unsignedInteger('latency_ms')
                ->nullable();




            $table->text('error_message')
                ->nullable();




            $table->timestamp('started_at')
                ->nullable();




            $table->timestamp('completed_at')
                ->nullable();




            $table->timestamps();




            $table->unique([
                'campaign_id',
                'version',
            ]);




            $table->index([
                'business_id',
                'status',
            ]);




            $table->index([
                'campaign_id',
                'status',
            ]);




            $table->index('research_run_id');
        });
    }




    public function down(): void
    {
        Schema::dropIfExists(
            'campaign_strategies'
        );
    }
};
