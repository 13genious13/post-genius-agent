<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('social_publication_events',function(Blueprint $t){
 $t->id();$t->foreignId('social_publication_id')->constrained()->cascadeOnDelete();$t->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
 $t->string('event',60);$t->string('from_status',30)->nullable();$t->string('to_status',30)->nullable();$t->text('message')->nullable();$t->json('data')->nullable();$t->timestamps();
 });}
 public function down():void {Schema::dropIfExists('social_publication_events');}
};
