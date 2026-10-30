<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('optimization_runs',function(Blueprint $t){
  $t->id();$t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('campaign_id')->constrained()->cascadeOnDelete();
  $t->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();$t->foreignId('campaign_strategy_id')->nullable()->constrained()->nullOnDelete();
  $t->string('status',30)->default('pending');$t->string('idempotency_key',64)->unique();$t->string('analysis_window',40)->default('latest_snapshots');
  $t->unsignedInteger('eligible_posts')->default(0);$t->json('evidence_snapshot')->nullable();$t->json('summary')->nullable();
  $t->text('error_message')->nullable();$t->timestamp('started_at')->nullable();$t->timestamp('completed_at')->nullable();$t->timestamps();
  $t->index(['business_id','campaign_id','status']);
 });}
 public function down():void {Schema::dropIfExists('optimization_runs');}
};
