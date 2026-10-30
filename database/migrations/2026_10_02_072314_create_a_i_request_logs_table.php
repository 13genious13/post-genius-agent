<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_request_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('business_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('provider');
            $table->string('model')->nullable();

            $table->string('feature')->nullable();

            $table->string('status')
                ->default('pending');

            $table->unsignedInteger('prompt_tokens')
                ->nullable();

            $table->unsignedInteger('completion_tokens')
                ->nullable();

            $table->unsignedInteger('total_tokens')
                ->nullable();

            $table->unsignedInteger('latency_ms')
                ->nullable();

            $table->decimal(
                'estimated_cost',
                12,
                6
            )->nullable();

            $table->text('error_message')
                ->nullable();

            $table->json('metadata')
                ->nullable();

            $table->timestamps();

            $table->index([
                'provider',
                'created_at',
            ]);

            $table->index([
                'feature',
                'created_at',
            ]);

            $table->index([
                'business_id',
                'created_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_request_logs');
    }
};
