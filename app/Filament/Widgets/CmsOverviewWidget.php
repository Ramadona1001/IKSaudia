<?php

namespace App\Filament\Widgets;

use App\Models\CareerApplication;
use App\Models\ContactSubmission;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CmsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make(__('cms.widgets.published_pages'), Page::query()->published()->count())
                ->description(__('cms.widgets.cms_pages'))
                ->color('primary'),
            Stat::make(__('cms.widgets.services'), Service::query()->published()->count())
                ->description(__('cms.widgets.live_on_site'))
                ->color('success'),
            Stat::make(__('cms.widgets.projects'), Project::query()->published()->count())
                ->color('info'),
            Stat::make(__('cms.widgets.new_enquiries'), ContactSubmission::query()->where('status', 'new')->count())
                ->description(__('cms.widgets.contact_form'))
                ->color('warning'),
            Stat::make(__('cms.widgets.job_applications'), CareerApplication::query()->where('status', 'new')->count())
                ->color('danger'),
        ];
    }
}
