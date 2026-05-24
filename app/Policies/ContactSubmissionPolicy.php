<?php

namespace App\Policies;

use App\Models\ContactSubmission;
use App\Models\User;

class ContactSubmissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->hasAnyRole(['super_admin', 'admin', 'editor']);
    }

    public function view(User $user, ContactSubmission $submission): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, ContactSubmission $submission): bool
    {
        return $user->is_active && $user->hasAnyRole(['super_admin', 'admin']);
    }

    public function delete(User $user, ContactSubmission $submission): bool
    {
        return $user->hasRole('super_admin');
    }
}
