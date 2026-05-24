<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactSubmissionRequest;
use App\Jobs\NotifyContactSubmission;
use App\Models\ContactSubmission;
use App\Services\LocaleService;
use App\Services\Security\SecurityEventLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        protected LocaleService $locales,
        protected SecurityEventLogger $security,
    ) {}

    public function show(string $locale): View
    {
        return view('front.contact', [
            'locales' => $this->locales->active(),
            'formStartedAt' => time(),
        ]);
    }

    public function store(StoreContactSubmissionRequest $request, string $locale): RedirectResponse
    {
        if ($request->isHoneypotTriggered() || $request->isTooFastSubmission()) {
            $this->security->contactSpamBlocked($request, [
                'honeypot' => $request->isHoneypotTriggered(),
                'fast' => $request->isTooFastSubmission(),
            ]);

            return redirect()
                ->route('contact', $locale)
                ->with('contact_success', 'IK-'.strtoupper(Str::random(8)));
        }

        $submission = ContactSubmission::query()->create([
            ...$request->safe()->except(['website', 'form_started_at']),
            'reference_number' => 'IK-'.strtoupper(Str::random(8)),
            'locale' => $locale,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500),
        ]);

        NotifyContactSubmission::dispatch($submission);

        return redirect()
            ->route('contact', $locale)
            ->with('contact_success', __('contact.success_reference', [
                'reference' => $submission->reference_number,
            ]));
    }
}
