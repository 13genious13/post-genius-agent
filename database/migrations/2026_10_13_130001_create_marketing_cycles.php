<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::create('marketing_cycles',function(Blueprint $t){
   $t->id();$t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('campaign_id')->constrained()->cascadeOnDelete();
   $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();$t->string('status',32)->default('draft');
   $t->string('idempotency_key',100);$t->string('stage',50)->default('planning');$t->unsignedInteger('step_count')->default(0);
   $t->json('snapshot')->nullable();$t->text('last_error')->nullable();$t->timestamp('started_at')->nullable();$t->timestamp('completed_at')->nullable();$t->timestamps();
   $t->unique(['business_id','idempotency_key']);$t->index(['business_id','campaign_id','status']);
  });
  Schema::create('marketing_cycle_steps',function(Blueprint $t){
   $t->id();$t->foreignId('marketing_cycle_id')->constrained()->cascadeOnDelete();$t->string('step_key',60);$t->string('action_key',80)->nullable();
   $t->string('status',32)->default('pending');$t->foreignId('agent_action_request_id')->nullable()->constrained()->nullOnDelete();
   $t->json('input')->nullable();$t->json('output')->nullable();$t->text('error_message')->nullable();$t->timestamp('started_at')->nullable();$t->timestamp('finished_at')->nullable();$t->timestamps();
   $t->unique(['marketing_cycle_id','step_key']);
  });
  Schema::create('agent_action_executions',function(Blueprint $t){
   $t->id();$t->foreignId('agent_action_request_id')->unique()->constrained()->cascadeOnDelete();$t->foreignId('marketing_cycle_id')->nullable()->constrained()->nullOnDelete();
   $t->string('status',32)->default('queued');$t->string('handler',100);$t->json('result')->nullable();$t->text('error_message')->nullable();
   $t->timestamp('claimed_at')->nullable();$t->timestamp('finished_at')->nullable();$t->timestamps();
  });
 }
 public function down():void {Schema::dropIfExists('agent_action_executions');Schema::dropIfExists('marketing_cycle_steps');Schema::dropIfExists('marketing_cycles');}
};
