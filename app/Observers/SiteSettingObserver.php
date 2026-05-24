<?php

namespace App\Observers;

use App\Models\SiteSetting;
use App\Models\SiteSettingTranslation;
use App\Services\SettingsService;

class SiteSettingObserver
{
  public function saved(SiteSetting|SiteSettingTranslation $model): void
  {
    app(SettingsService::class)->clearCache();
  }

  public function deleted(SiteSetting|SiteSettingTranslation $model): void
  {
    app(SettingsService::class)->clearCache();
  }
}
