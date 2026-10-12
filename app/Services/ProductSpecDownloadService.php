<?php

namespace App\Services;

use App\Jobs\NotifyProductSpecDownloadApproved;
use App\Jobs\NotifyProductSpecDownloadRequest;
use App\Models\ProductSpecDownloadRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductSpecDownloadService
{
    public function create(array $data): ProductSpecDownloadRequest
    {
        $request = ProductSpecDownloadRequest::query()->create([
            ...$data,
            'reference_number' => 'PDF-'.strtoupper(Str::random(8)),
            'status' => 'pending',
        ]);

        $this->notifySafely(
            fn () => NotifyProductSpecDownloadRequest::dispatchSync($request),
            'product_spec.request_notify_failed',
            ['reference' => $request->reference_number],
        );

        return $request;
    }

    public function approve(ProductSpecDownloadRequest $request, User $reviewer): ProductSpecDownloadRequest
    {
        $request->update([
            'status' => 'approved',
            'download_token' => Str::random(64),
            'download_token_expires_at' => now()->addDays(7),
            'approved_at' => now(),
            'rejected_at' => null,
            'reviewed_by' => $reviewer->id,
        ]);

        $fresh = $request->fresh(['product.translations']);

        $this->notifySafely(
            fn () => NotifyProductSpecDownloadApproved::dispatchSync($fresh),
            'product_spec.approved_notify_failed',
            ['reference' => $fresh?->reference_number],
        );

        return $fresh;
    }

    public function reject(ProductSpecDownloadRequest $request, User $reviewer, ?string $adminNotes = null): ProductSpecDownloadRequest
    {
        $request->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'approved_at' => null,
            'download_token' => null,
            'download_token_expires_at' => null,
            'reviewed_by' => $reviewer->id,
            'admin_notes' => $adminNotes ?? $request->admin_notes,
        ]);

        return $request->fresh();
    }

    public function findByToken(string $token): ?ProductSpecDownloadRequest
    {
        return ProductSpecDownloadRequest::query()
            ->where('download_token', $token)
            ->with(['product'])
            ->first();
    }

    /**
     * @param  callable(): mixed  $callback
     * @param  array<string, mixed>  $context
     */
    protected function notifySafely(callable $callback, string $event, array $context = []): void
    {
        try {
            $callback();
        } catch (\Throwable $exception) {
            Log::error($event, [
                ...$context,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
