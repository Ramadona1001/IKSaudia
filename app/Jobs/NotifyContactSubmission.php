<?php

namespace App\Jobs;

use App\Models\ContactSubmission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\Security\SecurityEventLogger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyContactSubmission implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public ContactSubmission $submission,
    ) {}

    public function handle(): void
    {
        $recipients = collect(setting('contact.form_recipients', []))
            ->pluck('email')
            ->filter()
            ->all();

        $to = $recipients[0] ?? config('mail.contact_notify', config('mail.from.address'));

        if (! $to || config('mail.default') === 'log') {
            Log::info('Contact submission received', [
                'reference' => $this->submission->reference_number,
                'email' => $this->submission->email,
            ]);

            return;
        }

        Mail::raw(
            "New contact submission\n\nReference: {$this->submission->reference_number}\nName: {$this->submission->name}\nEmail: {$this->submission->email}\n\n{$this->submission->message}",
            function ($message) use ($to, $recipients) {
                $message->to($to)->subject('[IK Saudi] Contact: '.$this->submission->reference_number);
                if (count($recipients) > 1) {
                    $message->cc(array_slice($recipients, 1));
                }
            }
        );
    }

    public function failed(?\Throwable $exception): void
    {
        app(SecurityEventLogger::class)->log('contact.notification_failed', [
            'reference' => $this->submission->reference_number,
            'error' => $exception?->getMessage(),
        ]);
    }
}
