<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_runs', function (Blueprint $table) {
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




            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();




            $table->unsignedInteger('version')
                ->default(1);




            $table->string('status')
                ->default('pending');




            $table->string('research_type')
                ->default('campaign');




            $table->text('objective');




            $table->json('research_plan')
                ->nullable();




            $table->text('executive_summary')
                ->nullable();




            $table->json('market_insights')
                ->nullable();




            $table->json('audience_insights')
                ->nullable();




            $table->json('competitor_insights')
                ->nullable();




            $table->json('trend_insights')
                ->nullable();




            $table->json('opportunities')
                ->nullable();




            $table->json('risks')
                ->nullable();




            $table->json('recommendations')
                ->nullable();




            $table->json('unknowns')
                ->nullable();




            $table->json('raw_analysis')
                ->nullable();




            $table->string('ai_provider')
                ->nullable();




            $table->string('ai_model')
                ->nullable();




            $table->string('search_provider')
                ->nullable();




            $table->unsignedInteger('query_count')
                ->default(0);




            $table->unsignedInteger('source_count')
                ->default(0);




            $table->unsignedInteger('successful_queries')
                ->default(0);




            $table->unsignedInteger('failed_queries')
                ->default(0);




            $table->unsignedBigInteger('total_tokens')
                ->nullable();




            $table->unsignedBigInteger('total_latency_ms')
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
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('research_runs');
    }
};
