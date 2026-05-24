<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectTranslation;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'client_name' => 'Major Operator',
                'year' => 2024,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'مشروع كشط خطوط أنابيب',
                        'slug' => 'pipeline-scraping-project',
                        'summary' => 'تنفيذ عمليات كشط وصيانة لخطوط أنابيب رئيسية في المنطقة الشرقية.',
                        'body' => '<p>مشروع شامل لصيانة وكشط خطوط نقل النفط في المنطقة الشرقية، مع الالتزام بمعايير السلامة والجودة.</p>',
                        'highlights' => ['تنفيذ وفق معايير API', 'فريق ميداني معتمد', 'تسليم في الوقت المحدد'],
                    ],
                    'en' => [
                        'title' => 'Pipeline Scraping Deployment',
                        'slug' => 'pipeline-scraping-project',
                        'summary' => 'Scraping and maintenance operations on major transmission pipelines in the Eastern Province.',
                        'body' => '<p>Comprehensive scraping and maintenance of oil transmission pipelines in the Eastern Province, delivered to API standards with zero lost-time incidents.</p>',
                        'highlights' => ['API-compliant execution', 'Certified field teams', 'On-time delivery'],
                    ],
                ],
            ],
            [
                'client_name' => 'Offshore Client',
                'year' => 2023,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'حلول بولي يوريثان تحت البحرية',
                        'slug' => 'subsea-polyurethane',
                        'summary' => 'توريد منتجات بولي يوريثان للبنية التحتية تحت البحرية.',
                        'body' => '<p>توريد وتركيب منتجات بولي يوريثان عالية الأداء للبنية التحتية تحت البحرية.</p>',
                        'highlights' => ['مواد مقاومة للتآكل', 'دعم فني ميداني', 'مطابقة لمواصفات العميل'],
                    ],
                    'en' => [
                        'title' => 'Subsea Polyurethane Solutions',
                        'slug' => 'subsea-polyurethane',
                        'summary' => 'Supply of polyurethane products for subsea infrastructure.',
                        'body' => '<p>Supply and installation of high-performance polyurethane products for subsea infrastructure applications.</p>',
                        'highlights' => ['Corrosion-resistant materials', 'Field technical support', 'Client specification compliance'],
                    ],
                ],
            ],
        ];

        foreach ($projects as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $slug = $translations['en']['slug'];
            $existing = ProjectTranslation::query()->where('slug', $slug)->where('locale', 'en')->first();

            $project = $existing?->project ?? new Project;
            $project->fill(array_merge($data, [
                'is_published' => true,
                'published_at' => $project->published_at ?? now(),
                'sort_order' => $project->sort_order ?? 1,
            ]));
            $project->save();

            foreach ($translations as $locale => $fields) {
                ProjectTranslation::query()->updateOrCreate(
                    ['project_id' => $project->id, 'locale' => $locale],
                    $fields,
                );
            }
        }
    }
}
