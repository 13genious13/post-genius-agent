<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_tool_calls', function (Blueprint $table) {
            $table->id();




            $table->foreignId('agent_run_id')
                ->constrained()
                ->cascadeOnDelete();




            $table->foreignId('agent_task_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();




            $table->string('agent_key', 100)
                ->nullable();




            $table->string('tool_key', 150);




            $table->string('status', 30)
                ->default('pending');




            $table->json('arguments')
                ->nullable();




            $table->json('result')
                ->nullable();




            $table->text('error_message')
                ->nullable();




            $table->unsignedInteger('latency_ms')
                ->nullable();




            $table->timestamp('started_at')
                ->nullable();




            $table->timestamp('completed_at')
                ->nullable();




            $table->timestamps();




            $table->index([
                'agent_run_id',
                'tool_key',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists(
            'agent_tool_calls'
        );
    }
};
