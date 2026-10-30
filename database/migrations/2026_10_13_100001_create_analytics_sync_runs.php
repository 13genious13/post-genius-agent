<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('analytics_sync_runs',function(Blueprint $t){
  $t->id();$t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('social_account_id')->nullable()->constrained()->nullOnDelete();
  $t->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();$t->string('status',24)->default('pending');$t->string('trigger',24)->default('manual');
  $t->unsignedInteger('posts_scanned')->default(0);$t->unsignedInteger('posts_succeeded')->default(0);$t->unsignedInteger('posts_failed')->default(0);$t->text('error_message')->nullable();
  $t->timestamp('started_at')->nullable();$t->timestamp('completed_at')->nullable();$t->timestamps();$t->index(['business_id','created_at']);
 });}
 public function down():void {Schema::dropIfExists('analytics_sync_runs');}
};
