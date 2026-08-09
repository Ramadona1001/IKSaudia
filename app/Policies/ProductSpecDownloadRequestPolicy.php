<?php

namespace App\Policies;

use App\Models\ProductSpecDownloadRequest;
use App\Models\User;

class ProductSpecDownloadRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->hasAnyRole(['super_admin', 'admin', 'editor']);
    }

    public function view(User $user, ProductSpecDownloadRequest $request): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, ProductSpecDownloadRequest $request): bool
    {
        return $user->is_active && $user->hasAnyRole(['super_admin', 'admin']);
    }

    public function delete(User $user, ProductSpecDownloadRequest $request): bool
    {
        return $user->hasRole('super_admin');
    }
}
