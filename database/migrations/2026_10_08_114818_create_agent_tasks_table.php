<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_tasks', function (Blueprint $table) {
            $table->id();




            $table->foreignId('agent_run_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->foreignId('strategy_action_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();




            $table->foreignId('parent_task_id')
                ->nullable()
                ->constrained('agent_tasks')
                ->nullOnDelete();




            $table->string('agent_key', 100);




            $table->string('task_type', 100);




            $table->string('title');




            $table->text('description')
                ->nullable();




            $table->string('status', 40)
                ->default('pending');




            $table->string('priority', 30)
                ->default('medium');




            $table->boolean('requires_approval')
                ->default(false);




            $table->unsignedInteger('sort_order')
                ->default(0);




            $table->json('input')
                ->nullable();




            $table->json('output')
                ->nullable();




            $table->json('metadata')
                ->nullable();




            $table->text('error_message')
                ->nullable();




            $table->timestamp('started_at')
                ->nullable();




            $table->timestamp('completed_at')
                ->nullable();




            $table->timestamps();




            $table->index([
                'agent_run_id',
                'status',
            ]);




            $table->index([
                'agent_run_id',
                'agent_key',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists('agent_tasks');
    }
};
