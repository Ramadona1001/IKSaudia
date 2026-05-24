<?php

namespace Database\Seeders;

use App\Services\NavigationService;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        app(NavigationService::class)->syncFromForm([
            [
                'label_ar' => 'الخدمات',
                'label_en' => 'Services',
                'link_type' => 'route',
                'route_name' => 'services.index',
                'is_mega_menu' => true,
                'is_visible' => true,
                'sort_order' => 0,
            ],
            [
                'label_ar' => 'من نحن',
                'label_en' => 'About',
                'link_type' => 'route',
                'route_name' => 'page.show',
                'page_slug' => 'about-us',
                'is_mega_menu' => false,
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'label_ar' => 'المشاريع',
                'label_en' => 'Projects',
                'link_type' => 'route',
                'route_name' => 'projects.index',
                'is_mega_menu' => false,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'label_ar' => 'العملية',
                'label_en' => 'Process',
                'link_type' => 'anchor',
                'url' => '#process',
                'is_mega_menu' => false,
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'label_ar' => 'اتصل بنا',
                'label_en' => 'Contact',
                'link_type' => 'route',
                'route_name' => 'contact',
                'is_mega_menu' => false,
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ]);
    }
}
