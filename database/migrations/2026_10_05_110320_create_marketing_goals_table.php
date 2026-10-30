<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();




            $table->string('name');




            $table->string('goal_type');




            $table->text('description')->nullable();




            $table->string('status')
                ->default('draft');




            $table->string('primary_kpi')
                ->nullable();




            $table->decimal('target_value', 15, 2)
                ->nullable();




            $table->decimal('current_value', 15, 2)
                ->nullable();




            $table->string('unit', 50)
                ->nullable();




            $table->json('secondary_kpis')
                ->nullable();




            $table->json('target_audience_ids')
                ->nullable();




            $table->json('target_locations')
                ->nullable();




            $table->json('platforms')
                ->nullable();




            $table->decimal('budget', 15, 2)
                ->nullable();




            $table->string('currency', 10)
                ->default('USD');




            $table->date('start_date')
                ->nullable();




            $table->date('end_date')
                ->nullable();




            $table->unsignedTinyInteger('priority')
                ->default(3);




            $table->text('constraints')
                ->nullable();




            $table->text('success_definition')
                ->nullable();




            $table->timestamps();




            $table->index([
                'business_id',
                'status',
            ]);




            $table->index([
                'business_id',
                'goal_type',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('marketing_goals');
    }
};
