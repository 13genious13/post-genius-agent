<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_channels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('channel');

            $table->string('handle')->nullable();

            $table->string('url')->nullable();

            $table->unsignedInteger('followers')->nullable();

            $table->boolean('is_active')->default(true);

            $table->boolean('is_priority')->default(false);

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->unique([
                'business_id',
                'channel',
                'handle',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_channels');
    }
};
