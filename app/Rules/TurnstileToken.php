<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class TurnstileToken implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! config('security.turnstile.enabled', false)) {
            return;
        }

        $secret = config('security.turnstile.secret_key');

        if (! $secret) {
            $fail('Turnstile is misconfigured.');

            return;
        }

        $response = Http::asForm()
            ->timeout(5)
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => (string) $value,
                'remoteip' => request()->ip(),
            ]);

        if (! $response->successful() || ! ($response->json('success') === true)) {
            $fail('Security verification failed. Please try again.');
        }
    }
}
