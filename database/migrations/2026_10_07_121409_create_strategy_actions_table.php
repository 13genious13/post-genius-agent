<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategy_actions', function (Blueprint $table) {
            $table->id();




            $table->foreignId('campaign_strategy_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->string('action_type', 100);




            $table->string('title');




            $table->text('description')
                ->nullable();




            $table->string('platform', 50)
                ->nullable();




            $table->string('funnel_stage', 50)
                ->nullable();




            $table->string('priority', 30)
                ->default('medium');




            $table->unsignedInteger('sort_order')
                ->default(0);




            $table->json('instructions')
                ->nullable();




            $table->json('success_metrics')
                ->nullable();




            $table->string('status')
                ->default('planned');




            $table->timestamps();




            $table->index([
                'campaign_strategy_id',
                'status',
            ]);




            $table->index([
                'campaign_strategy_id',
                'priority',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists(
            'strategy_actions'
        );
    }
};
