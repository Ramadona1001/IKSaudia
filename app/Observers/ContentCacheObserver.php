<?php

namespace App\Observers;

use App\Models\Certification;
use App\Models\CertificationTranslation;
use App\Models\Client;
use App\Models\ClientTranslation;
use App\Models\HomeSection;
use App\Models\HomeSectionItem;
use App\Models\HomeSectionItemTranslation;
use App\Models\HomeSectionTranslation;
use App\Models\Industry;
use App\Models\IndustryTranslation;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Partner;
use App\Models\PartnerTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Project;
use App\Models\ProjectTranslation;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Services\ClientCatalogService;
use App\Services\ContentCacheService;
use App\Services\HomePageService;
use App\Services\PageService;
use App\Services\PartnerCatalogService;
use Illuminate\Database\Eloquent\Model;

class ContentCacheObserver
{
    public function __construct(
        protected ContentCacheService $cache,
        protected PageService $pages,
        protected HomePageService $homePage,
        protected ClientCatalogService $clients,
        protected PartnerCatalogService $partners,
    ) {}

    public function saved(Model $model): void
    {
        $this->clear($model);
    }

    public function deleted(Model $model): void
    {
        $this->clear($model);
    }

    protected function clear(Model $model): void
    {
        match ($model::class) {
            Page::class, PageTranslation::class => $this->clearPage($model),
            Service::class, ServiceTranslation::class => $this->cache->forgetServices(),
            HomeSection::class,
            HomeSectionTranslation::class,
            HomeSectionItem::class,
            HomeSectionItemTranslation::class => $this->homePage->clearCache(),
            Project::class, ProjectTranslation::class => $this->cache->forgetProjects(),
            Industry::class, IndustryTranslation::class => $this->cache->forgetIndustries(),
            Product::class => $this->cache->forgetProducts(),
            ProductTranslation::class => $this->clearProductTranslation($model),
            Certification::class, CertificationTranslation::class => $this->cache->forgetCertifications(),
            Client::class, ClientTranslation::class => $this->clients->clearCache(),
            Partner::class, PartnerTranslation::class => $this->partners->clearCache(),
            default => null,
        };
    }

    protected function clearProductTranslation(Model $model): void
    {
        if ($model instanceof ProductTranslation) {
            $this->cache->forgetProduct($model->locale, $model->slug);
        }

        $this->cache->forgetProducts();
    }

    protected function clearPage(Model $model): void
    {
        if ($model instanceof PageTranslation) {
            $this->cache->forgetPage($model->locale, $model->slug);

            return;
        }

        $model->loadMissing('translations');
        foreach ($model->translations as $translation) {
            $this->cache->forgetPage($translation->locale, $translation->slug);
        }
    }
}
