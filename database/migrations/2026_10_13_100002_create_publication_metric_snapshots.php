<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('publication_metric_snapshots',function(Blueprint $t){
  $t->id();$t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('social_publication_id')->constrained()->cascadeOnDelete();$t->foreignId('analytics_sync_run_id')->nullable()->constrained()->nullOnDelete();
  $t->date('snapshot_date');$t->string('platform',30);$t->unsignedBigInteger('reach')->nullable();$t->unsignedBigInteger('impressions')->nullable();
  $t->unsignedBigInteger('likes')->nullable();$t->unsignedBigInteger('comments')->nullable();$t->unsignedBigInteger('shares')->nullable();$t->unsignedBigInteger('saves')->nullable();
  $t->unsignedBigInteger('clicks')->nullable();$t->unsignedBigInteger('video_views')->nullable();$t->unsignedBigInteger('interactions')->nullable();
  $t->json('available_metrics')->nullable();$t->json('provider_metadata')->nullable();$t->timestamp('collected_at');$t->timestamps();
  $t->unique(['social_publication_id','snapshot_date'],'metric_post_day_unique');$t->index(['business_id','snapshot_date']);
 });}
 public function down():void {Schema::dropIfExists('publication_metric_snapshots');}
};
