<?php

namespace App\Services;

use App\Jobs\NotifyProductSpecDownloadApproved;
use App\Jobs\NotifyProductSpecDownloadRequest;
use App\Models\ProductSpecDownloadRequest;
use App\Models\User;
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

        NotifyProductSpecDownloadRequest::dispatch($request);

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

        NotifyProductSpecDownloadApproved::dispatch($request->fresh(['product.translations']));

        return $request->fresh();
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
}
