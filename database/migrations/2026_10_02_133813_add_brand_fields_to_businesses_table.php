<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {

            $table->string('business_type')
                ->nullable()
                ->after('industry');

            $table->string('tagline')
                ->nullable()
                ->after('name');

            $table->string('primary_language', 10)
                ->default('en');

            $table->text('mission')
                ->nullable();

            $table->text('vision')
                ->nullable();

            $table->text('unique_value_proposition')
                ->nullable();

            $table->text('brand_story')
                ->nullable();

            $table->json('target_locations')
                ->nullable();

            $table->json('keywords')
                ->nullable();

            $table->json('marketing_objectives')
                ->nullable();

            $table->string('brand_voice')
                ->nullable();

            $table->json('brand_traits')
                ->nullable();

            $table->json('words_to_use')
                ->nullable();

            $table->json('words_to_avoid')
                ->nullable();

            $table->string('primary_color', 20)
                ->nullable();

            $table->string('secondary_color', 20)
                ->nullable();

            $table->string('accent_color', 20)
                ->nullable();

            $table->string('logo_url')
                ->nullable();

            $table->boolean('onboarding_completed')
                ->default(false);

            $table->unsignedTinyInteger('profile_completion')
                ->default(0);

            $table->timestamp('intelligence_generated_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'business_type',
                'tagline',
                'primary_language',
                'mission',
                'vision',
                'unique_value_proposition',
                'brand_story',
                'target_locations',
                'keywords',
                'marketing_objectives',
                'brand_voice',
                'brand_traits',
                'words_to_use',
                'words_to_avoid',
                'primary_color',
                'secondary_color',
                'accent_color',
                'logo_url',
                'onboarding_completed',
                'profile_completion',
                'intelligence_generated_at',
            ]);
        });
    }
};
