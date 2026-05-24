<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\LocaleService;
use App\Services\ProductCatalogService;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductCatalogService $products,
        protected LocaleService $locales,
    ) {}

    public function index(): View
    {
        $locale = app()->getLocale();

        return view('front.products.index', [
            'products' => $this->products->publishedList($locale),
            'locales' => $this->locales->active(),
        ]);
    }

    public function show(string $slug): View
    {
        $locale = app()->getLocale();
        $product = $this->products->findPublishedBySlug($slug, $locale);

        abort_if($product === null, 404);

        return view('front.products.show', [
            'product' => $product,
            'translation' => $product->translate($locale),
            'seo' => $product->seoFor($locale),
            'locales' => $this->locales->active(),
        ]);
    }
}
