<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();

            $table->string('key')->unique();
            $table->string('name');

            $table->string('driver');

            $table->string('base_url')->nullable();

            $table->string('default_model')->nullable();

            $table->decimal(
                'temperature',
                4,
                2
            )->default(0.60);

            $table->unsignedInteger(
                'max_tokens'
            )->default(4096);

            $table->boolean('is_active')
                ->default(true);

            $table->boolean('is_default')
                ->default(false);

            $table->unsignedInteger('priority')
                ->default(100);

            $table->json('settings')
                ->nullable();

            $table->timestamp('last_tested_at')
                ->nullable();

            $table->boolean('last_test_success')
                ->nullable();

            $table->text('last_test_message')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_providers');
    }
};
