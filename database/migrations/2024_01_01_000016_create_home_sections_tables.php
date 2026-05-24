<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // hero, about_snippet, services_grid, etc.
            $table->string('type', 50);
            $table->json('settings')->nullable(); // layout, columns, autoplay, etc.
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('home_section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_section_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->json('content')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->timestamps();

            $table->unique(['home_section_id', 'locale']);
            $table->index('locale');
        });

        Schema::create('home_section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_section_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('linkable');
            $table->string('url')->nullable();
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['home_section_id', 'sort_order']);
        });

        Schema::create('home_section_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_section_item_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('button_text')->nullable();
            $table->timestamps();

            $table->unique(['home_section_item_id', 'locale'], 'home_section_item_locale_unique');
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_section_item_translations');
        Schema::dropIfExists('home_section_items');
        Schema::dropIfExists('home_section_translations');
        Schema::dropIfExists('home_sections');
    }
};
