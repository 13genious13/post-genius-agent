<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();




            $table->foreignId('business_id')
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




            $table->string('name');




            $table->string('campaign_type')
                ->default('integrated');




            $table->string('status')
                ->default('draft');




            $table->text('objective');




            $table->text('offer')
                ->nullable();




            $table->text('call_to_action')
                ->nullable();




            $table->json('target_audience_ids')
                ->nullable();




            $table->json('platforms')
                ->nullable();




            $table->json('target_locations')
                ->nullable();




            $table->json('content_formats')
                ->nullable();




            $table->json('primary_kpis')
                ->nullable();




            $table->decimal('budget', 15, 2)
                ->nullable();




            $table->string('currency', 10)
                ->default('USD');




            $table->date('start_date')
                ->nullable();




            $table->date('end_date')
                ->nullable();




            $table->text('brand_constraints')
                ->nullable();




            $table->text('campaign_constraints')
                ->nullable();




            $table->text('notes')
                ->nullable();




            $table->timestamp('brief_generated_at')
                ->nullable();




            $table->timestamps();




            $table->softDeletes();




            $table->index([
                'business_id',
                'status',
            ]);




            $table->index([
                'business_id',
                'start_date',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
