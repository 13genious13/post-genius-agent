<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_queries', function (Blueprint $table) {
            $table->id();




            $table->foreignId('research_run_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->string('category', 100)
                ->default('general');




            $table->text('query');




            $table->text('purpose')
                ->nullable();




            $table->string('status')
                ->default('pending');




            $table->unsignedInteger('sort_order')
                ->default(0);




            $table->unsignedInteger('result_count')
                ->default(0);




            $table->unsignedInteger('latency_ms')
                ->nullable();




            $table->text('error_message')
                ->nullable();




            $table->timestamp('searched_at')
                ->nullable();




            $table->timestamps();




            $table->index([
                'research_run_id',
                'status',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('research_queries');
    }
};
