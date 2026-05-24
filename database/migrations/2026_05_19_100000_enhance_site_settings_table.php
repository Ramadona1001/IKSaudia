<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('value')->nullable()->after('type');
            $table->boolean('is_translatable')->default(false)->after('value');
            $table->string('label')->nullable()->after('key');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['value', 'is_translatable', 'label']);
        });
    }
};
