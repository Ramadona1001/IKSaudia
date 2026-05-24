<?php

namespace App\Observers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemTranslation;
use App\Services\NavigationService;
use Illuminate\Database\Eloquent\Model;

class NavigationObserver
{
    public function saved(Model $model): void
    {
        app(NavigationService::class)->clearCache();
    }

    public function deleted(Model $model): void
    {
        app(NavigationService::class)->clearCache();
    }
}
