<?php

namespace App\Http\Requests;

use App\Rules\TurnstileToken;
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
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'company' => ['nullable', 'string', 'max:120'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
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
        return [
            'name' => __('contact.attributes.name'),
            'email' => __('contact.attributes.email'),
            'phone' => __('contact.attributes.phone'),
            'company' => __('contact.attributes.company'),
            'subject' => __('contact.attributes.subject'),
            'message' => __('contact.attributes.message'),
        ];
    }
}
