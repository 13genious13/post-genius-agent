<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_briefs', function (Blueprint $table) {
            $table->id();




            $table->foreignId('campaign_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->unsignedInteger('version')
                ->default(1);




            $table->string('status')
                ->default('generated');




            $table->string('provider')
                ->nullable();




            $table->string('model')
                ->nullable();




            $table->text('executive_summary')
                ->nullable();




            $table->text('strategic_direction')
                ->nullable();




            $table->text('core_message')
                ->nullable();




            $table->json('audience_strategy')
                ->nullable();




            $table->json('content_themes')
                ->nullable();




            $table->json('platform_strategy')
                ->nullable();




            $table->json('content_formats')
                ->nullable();




            $table->json('cta_strategy')
                ->nullable();




            $table->json('kpi_recommendations')
                ->nullable();




            $table->json('execution_priorities')
                ->nullable();




            $table->json('risks')
                ->nullable();




            $table->json('assumptions')
                ->nullable();




            $table->json('raw_structured_output')
                ->nullable();




            $table->timestamp('generated_at')
                ->nullable();




            $table->timestamps();




            $table->unique([
                'campaign_id',
                'version',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('campaign_briefs');
    }
};
