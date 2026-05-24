<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ValidateProductionEnvironmentCommand extends Command
{
    protected $signature = 'app:validate-production {--strict : Fail on warnings too}';

    protected $description = 'Validate production environment configuration (security baseline)';

    public function handle(): int
    {
        $errors = [];
        $warnings = [];

        if (! app()->environment('production')) {
            $warnings[] = 'APP_ENV is not "production".';
        }

        if (config('app.debug')) {
            $errors[] = 'APP_DEBUG must be false in production.';
        }

        if (blank(config('app.key'))) {
            $errors[] = 'APP_KEY is missing.';
        }

        if (! config('session.encrypt')) {
            $warnings[] = 'SESSION_ENCRYPT should be true in production.';
        }

        if (! config('session.secure')) {
            $warnings[] = 'SESSION_SECURE_COOKIE should be true behind HTTPS.';
        }

        if (config('mail.default') === 'log') {
            $warnings[] = 'MAIL_MAILER is "log" — contact notifications will not be emailed.';
        }

        if (config('queue.default') === 'sync') {
            $warnings[] = 'QUEUE_CONNECTION is "sync" — use database/redis with a worker.';
        }

        if (config('cache.default') === 'database') {
            $warnings[] = 'Consider CACHE_STORE=redis for production performance.';
        }

        if (config('security.csp.report_only', true) && config('security.csp.enabled', true)) {
            $warnings[] = 'CSP is in report-only mode — switch to enforce after tuning.';
        }

        if (config('security.admin.ip_allowlist_enabled') && config('security.admin.ip_allowlist') === []) {
            $errors[] = 'ADMIN_IP_ALLOWLIST_ENABLED is true but ADMIN_IP_ALLOWLIST is empty.';
        }

        foreach ($errors as $message) {
            $this->error('[ERROR] '.$message);
        }

        foreach ($warnings as $message) {
            $this->warn('[WARN] '.$message);
        }

        if ($errors !== []) {
            $this->line('');
            $this->error('Production validation failed.');

            return self::FAILURE;
        }

        if ($warnings !== [] && $this->option('strict')) {
            $this->error('Strict mode: warnings treated as failures.');

            return self::FAILURE;
        }

        $this->info('Production environment validation passed.');

        return self::SUCCESS;
    }
}
