<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_intelligences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('version')
                ->default(1);

            $table->string('status')
                ->default('generated');

            $table->string('provider')->nullable();

            $table->string('model')->nullable();

            $table->text('business_summary')->nullable();

            $table->text('market_position')->nullable();

            $table->text('value_proposition')->nullable();

            $table->json('key_strengths')->nullable();

            $table->json('opportunities')->nullable();

            $table->json('audience_insights')->nullable();

            $table->json('content_pillars')->nullable();

            $table->json('recommended_channels')->nullable();

            $table->json('brand_voice_guidelines')->nullable();

            $table->json('messaging_angles')->nullable();

            $table->json('risk_notes')->nullable();

            $table->json('raw_structured_output')->nullable();

            $table->timestamp('generated_at')->nullable();

            $table->timestamps();

            $table->unique([
                'business_id',
                'version',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_intelligences');
    }
};
