<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('optimization_recommendations',function(Blueprint $t){
  $t->id();$t->foreignId('optimization_run_id')->constrained()->cascadeOnDelete();$t->foreignId('business_id')->constrained()->cascadeOnDelete();
  $t->foreignId('campaign_id')->constrained()->cascadeOnDelete();$t->string('recommendation_key',100);$t->string('category',50);
  $t->string('title');$t->text('rationale');$t->text('proposed_change');$t->string('priority',12)->default('medium');
  $t->string('status',24)->default('proposed');$t->json('evidence_publication_ids');$t->json('supporting_metrics')->nullable();
  $t->string('confidence',12)->default('low');$t->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
  $t->text('review_note')->nullable();$t->timestamp('reviewed_at')->nullable();$t->timestamps();
  $t->unique(['optimization_run_id','recommendation_key'],'optimization_rec_run_key_unique');$t->index(['business_id','campaign_id','status'],  'opt_rec_biz_campaign_status_idx');
 });}
 public function down():void {Schema::dropIfExists('optimization_recommendations');}
};
