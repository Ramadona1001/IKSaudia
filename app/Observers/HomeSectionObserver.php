<?php

namespace App\Observers;

use App\Models\HomeSection;
use App\Services\HomePageService;

class HomeSectionObserver
{
    public function __construct(
        protected HomePageService $homePage,
    ) {}

    public function saved(HomeSection $homeSection): void
    {
        $this->homePage->clearCache();
    }

    public function deleted(HomeSection $homeSection): void
    {
        $this->homePage->clearCache();
    }
}
