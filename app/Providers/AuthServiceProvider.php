<?php

namespace App\Providers;

use App\Models\Career;
use App\Models\CareerApplication;
use App\Models\Certification;
use App\Models\Client;
use App\Models\ContactSubmission;
use App\Models\Gallery;
use App\Models\HomeSection;
use App\Models\Industry;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductSpecDownloadRequest;
use App\Models\Project;
use App\Models\Redirect;
use App\Models\Service;
use App\Models\ServiceEdge;
use App\Models\User;
use App\Policies\CareerPolicy;
use App\Policies\CmsContentPolicy;
use App\Policies\ContactSubmissionPolicy;
use App\Policies\ProductSpecDownloadRequestPolicy;
use App\Policies\SystemPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    protected array $policies = [
        Service::class => CmsContentPolicy::class,
        ServiceEdge::class => CmsContentPolicy::class,
        Project::class => CmsContentPolicy::class,
        Page::class => CmsContentPolicy::class,
        Industry::class => CmsContentPolicy::class,
        Product::class => CmsContentPolicy::class,
        Client::class => CmsContentPolicy::class,
        Partner::class => CmsContentPolicy::class,
        Certification::class => CmsContentPolicy::class,
        NewsPost::class => CmsContentPolicy::class,
        Gallery::class => CmsContentPolicy::class,
        HomeSection::class => CmsContentPolicy::class,
        Career::class => CareerPolicy::class,
        CareerApplication::class => CareerPolicy::class,
        ContactSubmission::class => ContactSubmissionPolicy::class,
        ProductSpecDownloadRequest::class => ProductSpecDownloadRequestPolicy::class,
        Redirect::class => SystemPolicy::class,
        Menu::class => SystemPolicy::class,
        MenuItem::class => SystemPolicy::class,
    ];

    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        Gate::before(function ($user, string $ability) {
            if (! $user instanceof User) {
                return null;
            }

            if (! $user->is_active) {
                return false;
            }

            if ($user->hasRole('super_admin')) {
                return true;
            }

            return null;
        });
    }
}
