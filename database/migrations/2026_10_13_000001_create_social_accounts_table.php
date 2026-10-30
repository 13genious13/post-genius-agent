<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('social_accounts',function(Blueprint $t){
 $t->id();$t->foreignId('business_id')->constrained()->cascadeOnDelete();$t->foreignId('connected_by')->nullable()->constrained('users')->nullOnDelete();
 $t->string('platform',30);$t->string('external_id',120);$t->string('name');$t->text('access_token');$t->timestamp('token_expires_at')->nullable();
 $t->string('status',30)->default('active');$t->json('metadata')->nullable();$t->timestamps();$t->unique(['business_id','platform','external_id'],'social_accounts_identity_unique');$t->index(['business_id','status']);
 });}
 public function down():void {Schema::dropIfExists('social_accounts');}
};
