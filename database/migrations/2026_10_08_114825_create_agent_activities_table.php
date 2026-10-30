<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_activities', function (Blueprint $table) {
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




            $table->string('event_type', 100);




            $table->string('level', 20)
                ->default('info');




            $table->string('title');




            $table->text('message')
                ->nullable();




            $table->json('data')
                ->nullable();




            $table->timestamps();




            $table->index([
                'agent_run_id',
                'created_at',
            ]);




            $table->index([
                'agent_run_id',
                'event_type',
            ]);
        });
    }




    public function down(): void
    {
        Schema::dropIfExists(
            'agent_activities'
        );
    }
};
