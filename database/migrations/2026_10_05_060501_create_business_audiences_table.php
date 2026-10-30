<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_audiences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('type')
                ->default('customer');

            $table->string('age_range')->nullable();

            $table->string('gender')->nullable();

            $table->json('locations')->nullable();

            $table->json('interests')->nullable();

            $table->json('pain_points')->nullable();

            $table->json('goals')->nullable();

            $table->json('objections')->nullable();

            $table->json('buying_triggers')->nullable();

            $table->json('preferred_channels')->nullable();

            $table->text('description')->nullable();

            $table->boolean('is_primary')->default(false);

            $table->timestamps();

            $table->index(['business_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_audiences');
    }
};
