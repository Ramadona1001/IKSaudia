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

        $lines = [
            'New contact submission',
            '',
            'Reference: '.$this->submission->reference_number,
            'Name: '.$this->submission->name,
        ];

        if (filled($this->submission->email)) {
            $lines[] = 'Email: '.$this->submission->email;
        }

        if (filled($this->submission->phone)) {
            $lines[] = 'Phone: '.$this->submission->phone;
        }

        if (filled($this->submission->company)) {
            $lines[] = 'Company: '.$this->submission->company;
        }

        if (filled($this->submission->subject)) {
            $lines[] = 'Subject: '.$this->submission->subject;
        }

        $lines[] = '';
        $lines[] = $this->submission->message;

        if (is_array($this->submission->custom_fields) && $this->submission->custom_fields !== []) {
            $lines[] = '';
            $lines[] = 'Additional fields:';

            foreach ($this->submission->custom_fields as $key => $value) {
                $lines[] = $key.': '.$value;
            }
        }

        Mail::raw(
            implode("\n", $lines),
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
