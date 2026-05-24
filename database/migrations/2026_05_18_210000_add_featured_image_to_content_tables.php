<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var list<string> */
    private array $tables = [
        'services',
        'industries',
        'certifications',
        'clients',
        'partners',
        'news_posts',
        'careers',
        'pages',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'featured_image')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table) {
                $table->string('featured_image')->nullable()->after('uuid');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasColumn($table, 'featured_image')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('featured_image');
            });
        }
    }
};
