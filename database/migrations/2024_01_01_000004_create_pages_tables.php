<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('key')->unique(); // about, privacy-policy (locale-neutral identifier)
            $table->string('template', 50)->default('default'); // default, full-width, landing
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'published_at']);
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->timestamps();

            $table->unique(['page_id', 'locale']);
            $table->unique(['locale', 'slug']);
            $table->index('locale');
        });

        Schema::create('page_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50); // hero, rich_text, cta, etc.
            $table->json('config')->nullable(); // non-translatable settings
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['page_id', 'sort_order']);
        });

        Schema::create('page_block_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_block_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->json('content')->nullable();
            $table->timestamps();

            $table->unique(['page_block_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_block_translations');
        Schema::dropIfExists('page_blocks');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
};
