<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::create('creative_assets',function(Blueprint $t){
   $t->id(); $t->foreignId('business_id')->constrained()->cascadeOnDelete();
   $t->foreignId('campaign_id')->constrained()->cascadeOnDelete();
   $t->foreignId('content_asset_id')->constrained()->cascadeOnDelete();
   $t->foreignId('agent_task_id')->nullable()->constrained()->nullOnDelete();
   $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
   $t->unsignedInteger('version'); $t->string('status',32)->default('queued');
   $t->string('platform',40); $t->string('asset_type',32)->default('image');
   $t->json('design_spec')->nullable(); $t->text('prompt')->nullable();
   $t->string('storage_disk',40)->nullable(); $t->string('storage_path')->nullable();
   $t->string('mime_type',80)->nullable(); $t->unsignedBigInteger('bytes')->nullable();
   $t->string('image_provider',80)->nullable(); $t->string('image_model')->nullable();
   $t->json('brand_review')->nullable(); $t->unsignedTinyInteger('brand_score')->nullable();
   $t->text('review_notes')->nullable(); $t->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
   $t->timestamp('reviewed_at')->nullable(); $t->text('error_message')->nullable(); $t->timestamps();
   $t->unique(['content_asset_id','version']); $t->index(['business_id','status']);
  });
  Schema::create('creative_approvals',function(Blueprint $t){
   $t->id(); $t->foreignId('creative_asset_id')->constrained()->cascadeOnDelete();
   $t->foreignId('actor_id')->constrained('users')->cascadeOnDelete();
   $t->string('decision',24); $t->text('notes')->nullable(); $t->timestamps();
  });
 }
 public function down():void {Schema::dropIfExists('creative_approvals'); Schema::dropIfExists('creative_assets');}
};
