<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('optimization_experiments',function(Blueprint $t){
  $t->id();$t->foreignId('optimization_recommendation_id')->unique()->constrained()->cascadeOnDelete();
  $t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('campaign_id')->constrained()->cascadeOnDelete();
  $t->string('status',24)->default('draft');$t->string('hypothesis');$t->text('test_plan');$t->json('success_criteria')->nullable();
  $t->json('baseline_metrics')->nullable();$t->text('result_notes')->nullable();$t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
  $t->timestamp('started_at')->nullable();$t->timestamp('ended_at')->nullable();$t->timestamps();
 });}
 public function down():void {Schema::dropIfExists('optimization_experiments');}
};
