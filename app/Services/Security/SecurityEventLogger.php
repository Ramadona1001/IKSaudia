<?php

namespace App\Services\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class SecurityEventLogger
{
    public function log(string $event, array $context = []): void
    {
        Log::channel('security')->warning($event, $this->sanitizeContext($context));
    }

    public function info(string $event, array $context = []): void
    {
        Log::channel('security')->info($event, $this->sanitizeContext($context));
    }

    public function failedLogin(?string $email, Request $request, ?string $reason = null): void
    {
        $this->log('auth.login_failed', [
            'email' => $this->maskEmail($email),
            'ip' => $request->ip(),
            'user_agent' => $this->truncateUserAgent($request),
            'reason' => $reason,
        ]);
    }

    public function successfulLogin(int $userId, Request $request): void
    {
        $this->info('auth.login_success', [
            'user_id' => $userId,
            'ip' => $request->ip(),
            'user_agent' => $this->truncateUserAgent($request),
        ]);
    }

    public function accountLocked(?string $email, Request $request): void
    {
        $this->log('auth.account_locked', [
            'email' => $this->maskEmail($email),
            'ip' => $request->ip(),
        ]);
    }

    public function contactSpamBlocked(Request $request, array $flags): void
    {
        $this->log('contact.spam_blocked', [
            'ip' => $request->ip(),
            'flags' => $flags,
        ]);
    }

    public function adminAccessDenied(Request $request, string $reason): void
    {
        $this->log('admin.access_denied', [
            'ip' => $request->ip(),
            'path' => $request->path(),
            'reason' => $reason,
        ]);
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    protected function sanitizeContext(array $context): array
    {
        $forbidden = ['password', 'password_confirmation', 'token', 'secret', 'two_factor_secret', 'two_factor_recovery_codes'];

        foreach ($forbidden as $key) {
            unset($context[$key]);
        }

        return $context;
    }

    protected function maskEmail(?string $email): ?string
    {
        if (! $email || ! str_contains($email, '@')) {
            return $email;
        }

        [$local, $domain] = explode('@', $email, 2);

        return substr($local, 0, 1).'***@'.$domain;
    }

    protected function truncateUserAgent(Request $request): string
    {
        return substr((string) $request->userAgent(), 0, 200);
    }
}
