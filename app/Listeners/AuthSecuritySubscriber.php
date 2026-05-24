<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\Security\SecurityEventLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Events\Dispatcher;

final class AuthSecuritySubscriber
{
    public function __construct(
        protected SecurityEventLogger $security,
    ) {}

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Failed::class, [$this, 'handleFailed']);
        $events->listen(Login::class, [$this, 'handleLogin']);
        $events->listen(Logout::class, [$this, 'handleLogout']);
    }

    public function handleFailed(Failed $event): void
    {
        $email = $event->credentials['email'] ?? null;
        $request = request();

        $user = User::query()->where('email', $email)->first();

        if ($user instanceof User) {
            $user->increment('failed_login_attempts');

            $max = (int) config('security.lockout.max_attempts', 5);
            $minutes = (int) config('security.lockout.decay_minutes', 15);

            if ($user->failed_login_attempts >= $max) {
                $user->forceFill([
                    'locked_until' => now()->addMinutes($minutes),
                ])->save();

                $this->security->accountLocked($email, $request);
            }
        }

        $this->security->failedLogin($email, $request, $user?->locked_until ? 'account_locked' : null);
    }

    public function handleLogin(Login $event): void
    {
        $user = $event->user;

        if (! $user instanceof User) {
            return;
        }

        if ($user->locked_until && $user->locked_until->isFuture()) {
            auth()->logout();

            return;
        }

        $user->forceFill([
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ])->save();

        $this->security->successfulLogin($user->id, request());
    }

    public function handleLogout(Logout $event): void
    {
        // Intentionally minimal — session invalidation handled by framework.
    }
}
