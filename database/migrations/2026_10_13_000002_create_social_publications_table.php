<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('social_publications',function(Blueprint $t){
 $t->id();$t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('campaign_id')->constrained()->cascadeOnDelete();
 $t->foreignId('content_asset_id')->constrained()->restrictOnDelete();$t->foreignId('creative_asset_id')->nullable()->constrained()->restrictOnDelete();$t->foreignId('social_account_id')->constrained()->restrictOnDelete();
 $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();$t->uuid('idempotency_key')->unique();
 $t->string('status',30)->default('draft');$t->timestamp('scheduled_at')->nullable();$t->timestamp('claimed_at')->nullable();$t->timestamp('published_at')->nullable();
 $t->unsignedTinyInteger('attempts')->default(0);$t->string('external_post_id',160)->nullable();$t->text('permalink')->nullable();
 $t->longText('caption_snapshot');$t->string('media_disk',40)->nullable();$t->string('media_path')->nullable();$t->text('media_public_url')->nullable();
 $t->text('last_error')->nullable();$t->json('provider_result')->nullable();$t->timestamps();
 $t->index(['status','scheduled_at']);$t->index(['business_id','created_at']);
 });}
 public function down():void {Schema::dropIfExists('social_publications');}
};
