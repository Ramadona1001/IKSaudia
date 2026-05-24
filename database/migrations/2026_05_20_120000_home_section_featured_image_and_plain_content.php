<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('home_sections', 'featured_image')) {
            Schema::table('home_sections', function (Blueprint $table) {
                $table->string('featured_image')->nullable()->after('type');
            });
        }

        DB::statement('ALTER TABLE home_section_translations MODIFY content LONGTEXT NULL');

        foreach (DB::table('home_section_translations')->get(['id', 'content']) as $row) {
            $raw = $row->content;
            if ($raw === null || $raw === '') {
                continue;
            }

            if (! is_string($raw)) {
                continue;
            }

            $trimmed = ltrim($raw);
            if ($trimmed === '' || ($trimmed[0] !== '{' && $trimmed[0] !== '[')) {
                continue;
            }

            $decoded = json_decode($raw, true);
            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                continue;
            }

            $text = $decoded['text'] ?? ($decoded['body'] ?? null);
            if ($text === null && $decoded !== []) {
                $text = collect($decoded)->filter(fn ($v) => is_string($v))->first();
            }

            if ($text !== null) {
                DB::table('home_section_translations')
                    ->where('id', $row->id)
                    ->update(['content' => $text]);
            }
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE home_section_translations MODIFY content JSON NULL');

        if (Schema::hasColumn('home_sections', 'featured_image')) {
            Schema::table('home_sections', function (Blueprint $table) {
                $table->dropColumn('featured_image');
            });
        }
    }
};
