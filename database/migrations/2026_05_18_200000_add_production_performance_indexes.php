<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->index('ip_address');
            $table->index('email');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index(['is_published', 'is_featured', 'sort_order'], 'projects_featured_listing_idx');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->index(['is_published', 'is_featured', 'sort_order'], 'services_featured_listing_idx');
        });

        Schema::table('industries', function (Blueprint $table) {
            $table->index(['is_published', 'is_featured', 'sort_order'], 'industries_featured_listing_idx');
        });
    }

    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['email']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('projects_featured_listing_idx');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('services_featured_listing_idx');
        });

        Schema::table('industries', function (Blueprint $table) {
            $table->dropIndex('industries_featured_listing_idx');
        });
    }
};
