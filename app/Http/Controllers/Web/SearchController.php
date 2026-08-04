<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\SiteSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected SiteSearchService $search,
    ) {}

    public function __invoke(string $locale, Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        $results = $this->search->search($locale, $query);

        return response()->json([
            'query' => $query,
            'results' => $results,
        ]);
    }
}
