<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('marketing_memories',function(Blueprint $t){
  $t->id();$t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('campaign_id')->nullable()->constrained()->cascadeOnDelete();
  $t->string('memory_key',120);$t->string('category',60);$t->string('title');$t->text('observation');$t->string('confidence',20)->default('low');
  $t->unsignedInteger('sample_size')->default(0);$t->json('evidence_publication_ids')->nullable();$t->json('metrics')->nullable();
  $t->string('status',24)->default('active');$t->timestamp('observed_at');$t->timestamps();
  $t->unique(['campaign_id','memory_key'],'campaign_memory_unique');$t->index(['business_id','status']);
 });}
 public function down():void {Schema::dropIfExists('marketing_memories');}
};
