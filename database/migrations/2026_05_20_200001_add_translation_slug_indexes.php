<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'page_translations',
            'service_translations',
            'project_translations',
            'industry_translations',
            'product_translations',
            'news_post_translations',
        ];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $index = str_replace('_translations', '', $table).'_locale_slug_idx';

                if (! $this->indexExists($table, $index)) {
                    $blueprint->index(['locale', 'slug'], $index);
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (! $this->indexExists('products', 'products_published_sort_idx')) {
                    $table->index(['is_published', 'sort_order'], 'products_published_sort_idx');
                }
            });
        }
    }

    public function down(): void
    {
        $map = [
            'page_translations' => 'page_locale_slug_idx',
            'service_translations' => 'service_locale_slug_idx',
            'project_translations' => 'project_locale_slug_idx',
            'industry_translations' => 'industry_locale_slug_idx',
            'product_translations' => 'product_locale_slug_idx',
            'news_post_translations' => 'news_post_locale_slug_idx',
        ];

        foreach ($map as $table => $index) {
            if (Schema::hasTable($table) && $this->indexExists($table, $index)) {
                Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropIndex($index));
            }
        }

        if (Schema::hasTable('products') && $this->indexExists('products', 'products_published_sort_idx')) {
            Schema::table('products', fn (Blueprint $table) => $table->dropIndex('products_published_sort_idx'));
        }
    }

    protected function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            $indexes = $connection->select("PRAGMA index_list('{$table}')");

            return collect($indexes)->contains(fn ($row) => ($row->name ?? null) === $index);
        }

        $database = $connection->getDatabaseName();

        $result = $connection->select(
            'SELECT COUNT(*) AS aggregate FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $index]
        );

        return (int) ($result[0]->aggregate ?? 0) > 0;
    }
};
