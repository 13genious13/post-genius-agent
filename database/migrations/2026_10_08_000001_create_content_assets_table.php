<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('content_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->string('platform', 40);
            $table->string('content_type', 50)->default('post');
            $table->string('status', 30)->default('draft');
            $table->string('title')->nullable();
            $table->text('hook')->nullable();
            $table->longText('caption');
            $table->text('call_to_action')->nullable();
            $table->json('hashtags')->nullable();
            $table->json('variants')->nullable();
            $table->json('creative_brief')->nullable();
            $table->json('metadata')->nullable();
            $table->string('provider', 60)->nullable();
            $table->string('model')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['agent_task_id','version']);
            $table->index(['business_id','status','created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('content_assets'); }
};
