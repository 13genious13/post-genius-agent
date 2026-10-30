<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_sources', function (Blueprint $table) {
            $table->id();




            $table->foreignId('research_run_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->foreignId('research_query_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();




            $table->string('title')
                ->nullable();




            $table->text('url');




            $table->string('domain')
                ->nullable();




            $table->longText('content')
                ->nullable();




            $table->text('snippet')
                ->nullable();




            $table->decimal('search_score', 8, 6)
                ->nullable();




            $table->string('source_type', 50)
                ->default('web');




            $table->string('credibility', 30)
                ->default('unrated');




            $table->boolean('used_in_analysis')
                ->default(false);




            $table->json('metadata')
                ->nullable();




            $table->timestamps();




            $table->index('research_run_id');




            $table->index([
                'research_run_id',
                'domain',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('research_sources');
    }
};
