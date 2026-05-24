<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_section_items', function (Blueprint $table) {
            $table->string('image')->nullable()->after('home_section_id');
        });

        Schema::table('home_section_item_translations', function (Blueprint $table) {
            $table->string('button_url')->nullable()->after('button_text');
            $table->string('secondary_button_text')->nullable()->after('button_url');
            $table->string('secondary_button_url')->nullable()->after('secondary_button_text');
        });
    }

    public function down(): void
    {
        Schema::table('home_section_item_translations', function (Blueprint $table) {
            $table->dropColumn(['button_url', 'secondary_button_text', 'secondary_button_url']);
        });

        Schema::table('home_section_items', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
