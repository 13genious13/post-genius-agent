<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::create('agent_action_requests',function(Blueprint $t){
   $t->id();$t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
   $t->foreignId('agent_run_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('agent_task_id')->nullable()->constrained()->nullOnDelete();
   $t->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();$t->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
   $t->string('action_key',80);$t->string('status',24)->default('pending');$t->string('idempotency_key',100);
   $t->json('payload');$t->json('policy_snapshot')->nullable();$t->text('reason')->nullable();$t->text('decision_note')->nullable();
   $t->timestamp('expires_at')->nullable();$t->timestamp('decided_at')->nullable();$t->timestamps();
   $t->unique(['business_id','idempotency_key'],'aar_business_idempotency_unique');$t->index(['business_id','status','created_at']);
  });
  Schema::create('agent_governance_events',function(Blueprint $t){
   $t->id();$t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('agent_action_request_id')->nullable()->constrained()->nullOnDelete();
   $t->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();$t->string('event_key',80);$t->json('details')->nullable();$t->timestamps();
   $t->index(['business_id','created_at']);
  });
  Schema::create('agent_governance_limits',function(Blueprint $t){
   $t->id();$t->foreignId('business_id')->unique()->constrained()->cascadeOnDelete();$t->unsignedInteger('max_daily_requests')->default(25);
   $t->unsignedInteger('max_pending_approvals')->default(25);$t->boolean('pause_all_agents')->default(false);
   $t->boolean('require_two_person_review')->default(false);$t->json('blocked_action_keys')->nullable();$t->timestamps();
  });
 }
 public function down():void {Schema::dropIfExists('agent_governance_events');Schema::dropIfExists('agent_action_requests');Schema::dropIfExists('agent_governance_limits');}
};
