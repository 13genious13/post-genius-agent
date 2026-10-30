<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategy_evidence', function (Blueprint $table) {
            $table->id();




            $table->foreignId('campaign_strategy_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->foreignId('research_source_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->string('decision_key', 150);




            $table->text('decision_summary')
                ->nullable();




            $table->string('evidence_role', 50)
                ->default('supporting');




            $table->timestamps();




            $table->unique(
                [
                    'campaign_strategy_id',
                    'research_source_id',
                    'decision_key',
                ],
                'strategy_evidence_unique'
            );




            $table->index([
                'campaign_strategy_id',
                'decision_key',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists(
            'strategy_evidence'
        );
    }
};
