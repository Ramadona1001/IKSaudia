<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faq_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('icon', 80)->default('bi-question-circle-fill');
            $table->string('color', 20)->default('gold');
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'sort_order']);
        });

        Schema::create('faq_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_category_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title');
            $table->timestamps();

            $table->unique(['faq_category_id', 'locale']);
            $table->index('locale');
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_category_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['faq_category_id', 'is_published', 'sort_order']);
        });

        Schema::create('faq_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('question');
            $table->text('answer');
            $table->timestamps();

            $table->unique(['faq_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_translations');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_category_translations');
        Schema::dropIfExists('faq_categories');
    }
};
