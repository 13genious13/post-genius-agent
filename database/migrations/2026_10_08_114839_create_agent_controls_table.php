<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_controls', function (Blueprint $table) {
            $table->id();




            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->string('autonomy_mode', 30)
                ->default('assisted');




            $table->boolean('allow_research')
                ->default(true);




            $table->boolean('allow_content_generation')
                ->default(true);




            $table->boolean('allow_creative_generation')
                ->default(true);




            $table->boolean('allow_scheduling')
                ->default(false);




            $table->boolean('allow_publishing')
                ->default(false);




            $table->boolean('require_content_approval')
                ->default(true);




            $table->boolean('require_creative_approval')
                ->default(true);




            $table->boolean('require_publish_approval')
                ->default(true);




            $table->unsignedInteger('max_actions_per_run')
                ->default(25);




            $table->unsignedInteger('max_ai_requests_per_run')
                ->default(50);




            $table->json('allowed_platforms')
                ->nullable();




            $table->json('settings')
                ->nullable();




            $table->timestamps();




            $table->unique('business_id');
        });
    }




    public function down(): void
    {
        Schema::dropIfExists(
            'agent_controls'
        );
    }
};
