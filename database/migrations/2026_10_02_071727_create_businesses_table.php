<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('industry')->nullable();

            $table->string('website')->nullable();

            $table->text('description')->nullable();

            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();

            $table->string('timezone')
                ->default('Asia/Kolkata');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('industry');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
