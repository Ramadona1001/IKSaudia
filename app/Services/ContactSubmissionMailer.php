<?php

namespace App\Services;

use App\Mail\ContactSubmissionAdminMail;
use App\Mail\ContactSubmissionCustomerMail;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ContactSubmissionMailer
{
    public function send(ContactSubmission $submission): ?string
    {
        $locale = $submission->locale ?: config('app.locale', 'en');
        $adminRecipients = $this->adminRecipients();
        $errors = [];

        if (config('mail.default') === 'log') {
            $errors[] = 'MAIL_MAILER is "log", so messages are written to the log instead of being sent.';
        } else {
            $adminUrl = url('/'.config('cms.admin_path', 'ik-admin').'/contact-submissions/'.$submission->id.'/edit');

            if ($adminRecipients === []) {
                $errors[] = 'No admin recipient is configured.';
            } else {
                try {
                    Mail::to($adminRecipients[0])
                        ->cc(array_slice($adminRecipients, 1))
                        ->send(new ContactSubmissionAdminMail($submission, $adminUrl));
                } catch (\Throwable $exception) {
                    $errors[] = 'Admin email: '.$exception->getMessage();
                    Log::error('contact.admin_mail_failed', [
                        'reference' => $submission->reference_number,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }

            if (filled($submission->email) && filter_var($submission->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($submission->email, $submission->name)
                        ->send(new ContactSubmissionCustomerMail($submission, $locale));
                } catch (\Throwable $exception) {
                    $errors[] = 'Customer email: '.$exception->getMessage();
                    Log::error('contact.customer_mail_failed', [
                        'reference' => $submission->reference_number,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }
        }

        if ($errors !== []) {
            $submission->forceFill([
                'admin_notes' => Str::limit(implode("\n", $errors), 2000, ''),
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
            ->push('sales@iksaudi.com')
            ->filter(fn ($email) => filled($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->map(fn ($email) => strtolower((string) $email))
            ->unique()
            ->values()
            ->all();
    }
}
