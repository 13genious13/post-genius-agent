<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_competitors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('website')->nullable();

            $table->text('notes')->nullable();

            $table->json('strengths')->nullable();

            $table->json('weaknesses')->nullable();

            $table->json('differentiators')->nullable();

            $table->boolean('is_primary')->default(false);

            $table->timestamps();

            $table->index(['business_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_competitors');
    }
};
