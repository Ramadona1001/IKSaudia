<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class LegacyRedirectController extends Controller
{
    public function __invoke(string $legacyPath): RedirectResponse
    {
        $fromPath = '/'.ltrim($legacyPath, '/');

        $redirect = Cache::remember(
            'redirect:'.md5($fromPath),
            3600,
            fn () => Redirect::query()
                ->where('is_active', true)
                ->where('from_path', $fromPath)
                ->firstOrFail()
        );

        $redirect->increment('hits');
        $redirect->update(['last_hit_at' => now()]);

        return redirect($redirect->to_path, $redirect->status_code);
    }
}
