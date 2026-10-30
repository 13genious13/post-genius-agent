<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_product_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type')->default('service');

            $table->string('name');

            $table->text('description')->nullable();

            $table->string('category')->nullable();

            $table->decimal('price_from', 12, 2)->nullable();

            $table->decimal('price_to', 12, 2)->nullable();

            $table->string('currency', 10)->nullable();

            $table->string('url')->nullable();

            $table->text('unique_selling_point')->nullable();

            $table->json('benefits')->nullable();

            $table->json('target_audiences')->nullable();

            $table->boolean('is_featured')->default(false);

            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['business_id', 'is_active']);
            $table->index(['business_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_product_services');
    }
};
