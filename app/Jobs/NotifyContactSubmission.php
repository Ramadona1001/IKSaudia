<?php

namespace App\Jobs;

use App\Mail\ContactSubmissionAdminMail;
use App\Mail\ContactSubmissionCustomerMail;
use App\Models\ContactSubmission;
use App\Services\Security\SecurityEventLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
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

    public function handle(): ?string
    {
        $locale = $this->submission->locale ?: config('app.locale', 'en');
        $adminRecipients = $this->adminRecipients();

        $errors = [];

        if (config('mail.default') === 'log') {
            $errors[] = 'MAIL_MAILER is "log", so messages are written to the log instead of being sent.';
        } else {
            $adminUrl = url('/'.config('cms.admin_path', 'ik-admin').'/contact-submissions/'.$this->submission->id.'/edit');

            if ($adminRecipients === []) {
                $errors[] = 'No admin recipient is configured.';
            } else {
                try {
                    Mail::to($adminRecipients[0])
                        ->cc(array_slice($adminRecipients, 1))
                        ->send(new ContactSubmissionAdminMail($this->submission, $adminUrl));
                } catch (\Throwable $exception) {
                    $errors[] = 'Admin email: '.$exception->getMessage();
                    Log::error('contact.admin_mail_failed', [
                        'reference' => $this->submission->reference_number,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }

            if (filled($this->submission->email) && filter_var($this->submission->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($this->submission->email, $this->submission->name)
                        ->send(new ContactSubmissionCustomerMail($this->submission, $locale));
                } catch (\Throwable $exception) {
                    $errors[] = 'Customer email: '.$exception->getMessage();
                    Log::error('contact.customer_mail_failed', [
                        'reference' => $this->submission->reference_number,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }
        }

        if ($errors !== []) {
            $this->submission->forceFill([
                'admin_notes' => \Illuminate\Support\Str::limit(implode("\n", $errors), 2000, ''),
            ])->save();
        }

        return $errors === [] ? null : implode("\n", $errors);
    }

    /**
     * @return list<string>
     */
    protected function adminRecipients(): array
    {
        return collect(setting('contact.form_recipients', []))
            ->pluck('email')
            ->merge([
                config('mail.contact_notify'),
                config('mail.from.address'),
            ])
            ->filter(fn ($email) => filled($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->map(fn ($email) => strtolower((string) $email))
            ->unique()
            ->values()
            ->all();
    }

    public function failed(?\Throwable $exception): void
    {
        app(SecurityEventLogger::class)->log('contact.notification_failed', [
            'reference' => $this->submission->reference_number,
            'error' => $exception?->getMessage(),
        ]);
    }
}
