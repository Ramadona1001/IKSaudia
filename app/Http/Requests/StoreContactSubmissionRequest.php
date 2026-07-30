<?php

namespace App\Http\Requests;

use App\Rules\TurnstileToken;
use App\Support\ContactForm;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            ...ContactForm::validationRules(),
            'form_started_at' => ['nullable', 'integer'],
            'cf-turnstile-response' => [config('security.turnstile.enabled') ? 'required' : 'nullable', 'string', new TurnstileToken],
            'terms' => ['accepted'],
        ];
    }

    public function isHoneypotTriggered(): bool
    {
        return filled($this->input('website'));
    }

    public function isTooFastSubmission(): bool
    {
        $started = (int) $this->input('form_started_at', 0);

        if ($started <= 0) {
            return false;
        }

        $minSeconds = (int) config('security.contact.min_submit_seconds', 3);

        return (time() - $started) < $minSeconds;
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ContactForm::validationAttributes();
    }
}
