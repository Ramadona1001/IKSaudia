<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\LocaleService;
use App\Services\NewsPostCatalogService;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(
        protected NewsPostCatalogService $news,
        protected LocaleService $locales,
    ) {}

    public function index(string $locale): View
    {
        return view('news.index', [
            'posts' => $this->news->publishedList($locale),
            'locales' => $this->locales->active(),
        ]);
    }

    public function show(string $locale, string $slug): View
    {
        $post = $this->news->findPublishedBySlug($slug, $locale);

        abort_if($post === null, 404);

        return view('news.show', [
            'post' => $post,
            'translation' => $post->translate($locale),
            'seo' => $post->seoFor($locale),
            'locales' => $this->locales->active(),
        ]);
    }
}
