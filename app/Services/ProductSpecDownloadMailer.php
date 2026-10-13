<?php

namespace App\Services;

use App\Mail\ProductSpecDownloadApprovedMail;
use App\Mail\ProductSpecDownloadRequestAdminMail;
use App\Mail\ProductSpecDownloadRequestCustomerMail;
use App\Models\ProductSpecDownloadRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProductSpecDownloadMailer
{
    public function sendRequest(ProductSpecDownloadRequest $request): ?string
    {
        $request->loadMissing(['product.translations']);

        $locale = $request->locale ?: config('app.locale', 'en');
        $productTitle = $request->product?->translate($locale)?->title
            ?? $request->product?->translate('en')?->title
            ?? 'Product';
        $adminRecipients = $this->adminRecipients();
        $errors = [];

        if (config('mail.default') === 'log') {
            return 'MAIL_MAILER is "log", so messages are written to the log instead of being sent.';
        }

        $adminUrl = url('/'.config('cms.admin_path', 'ik-admin').'/product-spec-download-requests/'.$request->id.'/edit');

        if ($adminRecipients === []) {
            $errors[] = 'No admin recipient is configured.';
        } else {
            try {
                Mail::to($adminRecipients[0])
                    ->cc(array_slice($adminRecipients, 1))
                    ->send(new ProductSpecDownloadRequestAdminMail($request, $productTitle, $adminUrl));
            } catch (\Throwable $exception) {
                $errors[] = 'Admin email: '.$exception->getMessage();
                Log::error('product_spec.admin_mail_failed', [
                    'reference' => $request->reference_number,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        if (filled($request->email) && filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($request->email, $request->name)
                    ->send(new ProductSpecDownloadRequestCustomerMail($request, $productTitle, $locale));
            } catch (\Throwable $exception) {
                $errors[] = 'Customer email: '.$exception->getMessage();
                Log::error('product_spec.customer_mail_failed', [
                    'reference' => $request->reference_number,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $errors === [] ? null : implode("\n", $errors);
    }

    public function sendApproved(ProductSpecDownloadRequest $request): void
    {
        $request->loadMissing(['product.translations']);

        if (! $request->tokenIsValid() || blank($request->email)) {
            return;
        }

        $locale = $request->locale ?: config('app.locale', 'en');
        $productTitle = $request->product?->translate($locale)?->title
            ?? $request->product?->translate('en')?->title
            ?? 'Product';
        $downloadUrl = route('products.spec-download', ['token' => $request->download_token]);

        Mail::to($request->email, $request->name)
            ->send(new ProductSpecDownloadApprovedMail($request, $productTitle, $downloadUrl, $locale));
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
