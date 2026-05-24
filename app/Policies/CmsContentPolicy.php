<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CmsContentPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->canManageContent($user);
    }

    public function view(User $user, Model $model): bool
    {
        return $this->canManageContent($user);
    }

    public function create(User $user): bool
    {
        return $this->canManageContent($user);
    }

    public function update(User $user, Model $model): bool
    {
        return $this->canManageContent($user);
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    public function restore(User $user, Model $model): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return $user->hasRole('super_admin');
    }

    protected function canManageContent(User $user): bool
    {
        return $user->is_active && $user->hasAnyRole(['super_admin', 'admin', 'editor']);
    }
}
