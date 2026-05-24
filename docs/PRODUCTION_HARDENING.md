# IK Saudi — Enterprise Production Hardening Audit

Aramco-vendor-grade readiness assessment and operational playbook for the Laravel 11 public site + Filament CMS.

**Audit date:** 2026-05-18  
**Stack:** Laravel 11 · PHP 8.2+ · Filament v5 · MySQL · Redis (recommended)

---

## Executive summary

| Area | Status | Notes |
|------|--------|-------|
| OWASP baseline | Improved | CSP (report-only), headers, CSRF, rate limits |
| Authentication | Good | Roles, `is_active`, panel gate; enable 2FA (roadmap) |
| File uploads | Improved | MIME/size limits on project images |
| Spam / abuse | Improved | Honeypot, timing, throttle, fake success on bots |
| Cache | Fixed | Targeted invalidation (no full `Cache::flush`) |
| Performance | Good | Indexes added; Redis recommended for prod |
| Observability | Partial | Security log channel; add APM in prod |

**Implemented in codebase:** `config/security.php`, enhanced `SecurityHeaders`, rate limiters, contact hardening, `ContentCacheService`, queue job for contact mail, DB indexes migration, Nginx example config.

---

## 1. Security audit checklist

### A01 Broken access control
- [x] Filament `canAccessPanel()` + Spatie roles
- [x] `super_admin` gate bypass documented
- [ ] Enable Filament policy resources per module (Shield optional)
- [ ] IP allowlist for `/ik-admin` (enterprise VPN) via Nginx
- [ ] Disable inactive users (`is_active`)

### A02 Cryptographic failures
- [x] `password` cast to `hashed`
- [x] `APP_KEY` required in production
- [x] HSTS header (HTTPS)
- [ ] `SESSION_ENCRYPT=true` in production
- [ ] TLS 1.2+ only at load balancer

### A03 Injection
- [x] Eloquent / query builder (no raw user SQL)
- [x] Blade `{{ }}` escaping for CMS text
- [x] `{!! !!}` only for trusted admin HTML — audit CMS body content
- [ ] HTML purifier for rich text in CMS (e.g. HTMLPurifier)

### A04 Insecure design
- [x] Contact rate limiting (5/min, 20/hr per IP)
- [x] Honeypot + minimum submit time
- [x] Admin login rate limit at Nginx layer
- [ ] CAPTCHA (Turnstile/hCaptcha) if spam persists

### A05 Security misconfiguration
- [ ] `APP_DEBUG=false` production
- [ ] `APP_ENV=production`
- [ ] Remove default admin password after deploy
- [x] Security headers middleware
- [ ] Disable directory listing / block `.env` at Nginx
- [ ] `php artisan config:cache` (never commit cached config with secrets)

### A06 Vulnerable components
- [ ] `composer audit` in CI weekly
- [ ] `npm audit` for Vite build chain
- [ ] Dependabot / Renovate enabled

### A07 Authentication failures
- [x] Bcrypt rounds via `BCRYPT_ROUNDS`
- [x] Session driver database/redis
- [x] `AuthenticateSession` on Filament panel
- [ ] Enforce 2FA for `super_admin` / `admin` (columns exist on users)
- [ ] Password complexity policy
- [ ] Account lockout after N failed logins

### A08 Software & data integrity
- [x] CSRF on web + Filament
- [ ] Signed URLs for sensitive actions
- [ ] Composer `--no-dev` on production deploy

### A09 Logging & monitoring
- [x] Security log channel (`storage/logs/security.log`)
- [ ] Centralized logs (ELK, CloudWatch, Datadog)
- [ ] Alert on 5xx spike, failed logins, queue failures

### A10 SSRF
- [x] No user-controlled outbound URLs in app code
- [ ] Restrict Filament URL fields if added later

### File upload (OWASP extension)
- [x] Images only, 5MB max, public disk scoped to `projects/`
- [ ] Store uploads outside webroot + signed URLs (S3 ideal)
- [ ] Virus scan queue job for uploads (ClamAV)
- [ ] Strip EXIF metadata on upload

---

## 2. Middleware improvements

| Middleware | Purpose |
|------------|---------|
| `SecurityHeaders` | HSTS, CSP, COOP, CORP, Permissions-Policy |
| `RedirectLegacyUrls` | 301 legacy `.html` URLs |
| `SetLocale` | Locale validation `ar\|en` |
| `throttle:contact` | Contact form abuse protection |
| Trust proxies | `TRUSTED_PROXIES` for load balancer |

**CSP rollout:**
1. Deploy with `SECURITY_CSP_REPORT_ONLY=true`
2. Monitor violations 1–2 weeks
3. Set `SECURITY_CSP_REPORT_ONLY=false` for public site
4. Keep admin panel on relaxed `security.csp.admin` directives

**Config:** `config/security.php`

---

## 3. Nginx configuration recommendations

See [`deploy/nginx/iksaudi.conf.example`](../deploy/nginx/iksaudi.conf.example).

| Setting | Recommendation |
|---------|----------------|
| TLS | TLS 1.2/1.3, strong ciphers, HSTS |
| `client_max_body_size` | 6M (matches upload limit) |
| `limit_req` | Contact + admin login zones |
| `limit_conn` | 20 per IP |
| Static assets | 30d cache, `immutable` for Vite hashed files |
| PHP-FPM | Unix socket, hide `X-Powered-By` |
| Block | `.env`, `vendor`, `storage` direct access |
| Gzip/Brotli | Enable for text assets |

**Additional:**
- Separate `www` → apex redirect
- WAF (Cloudflare, AWS WAF, F5) for DDoS L7
- OCSP stapling enabled

---

## 4. Laravel production optimization

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build

php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan icons:cache   # Filament
```

### `.env` production (minimum)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://iksaudi.com

DB_CONNECTION=mysql
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

LOG_CHANNEL=daily
LOG_LEVEL=warning

SECURITY_CSP_ENABLED=true
SECURITY_CSP_REPORT_ONLY=false
TRUSTED_PROXIES=*
```

### Opcache (php.ini)

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0  ; reload on deploy only
```

### Scheduler (cron)

```cron
* * * * * cd /var/www/iksaudi && php artisan schedule:run >> /dev/null 2>&1
```

---

## 5. Cache strategy

| Layer | Dev | Production |
|-------|-----|------------|
| Config | file | `config:cache` |
| Routes | file | `route:cache` |
| Views | compiled | `view:cache` |
| Application data | database | **Redis** |

### Application cache keys (TTL 3600s)

| Key pattern | Cleared by |
|-------------|------------|
| `home.sections.{locale}` | HomeSection observer |
| `services.featured.{locale}` | Service observer |
| `projects.featured.{locale}` | Project observer |
| `industries.featured.{locale}` | Industry observer |
| `certifications.featured.{locale}` | Certification observer |
| `page.{locale}.{slug}` | Page observer |
| `service.{locale}.{slug}` | Manual / future observer |
| `project.{locale}.{slug}` | Manual / future observer |

**Service:** `App\Services\ContentCacheService` — never use `Cache::flush()` in production.

**Redis tags (optional upgrade):** `Cache::tags(['public', 'services'])->flush()` when using Redis.

**HTTP cache:** Nginx static for `/build/*`; consider `Cache-Control` for public HTML via CDN with short TTL + cache bust on deploy.

---

## 6. Queue strategy

| Job | Queue | Purpose |
|-----|-------|---------|
| `NotifyContactSubmission` | `default` | Email staff on new contact |
| Future: image processing | `media` | Resize/optimize uploads |
| Future: sitemap generation | `low` | Nightly SEO |

### Supervisor (production)

```ini
[program:iksaudi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/iksaudi/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/iksaudi/storage/logs/worker.log
stopwaitsecs=3600
```

**Failed jobs:** `php artisan queue:failed-table` + monitor + `queue:retry` playbook.

**Do not use `sync` driver in production** for contact notifications.

---

## 7. Backup strategy

| Asset | Frequency | Retention | Method |
|-------|-----------|-----------|--------|
| MySQL | Daily full + hourly binlog | 30 days | `mysqldump` / Percona XtraBackup |
| `storage/app` | Daily | 30 days | rsync / S3 sync |
| `.env` | On change | Secure vault | HashiCorp Vault / AWS SM |
| Code | Git tags | Indefinite | GitHub releases |

### Example backup script

```bash
#!/bin/bash
DATE=$(date +%Y%m%d)
mysqldump --single-transaction iksaudi | gzip > /backups/db/iksaudi-$DATE.sql.gz
tar -czf /backups/storage/iksaudi-storage-$DATE.tar.gz /var/www/iksaudi/storage/app
aws s3 sync /backups/ s3://iksaudi-backups-prod/ --sse AES256
```

**Test restores quarterly** — enterprise requirement.

---

## 8. Monitoring recommendations

| Signal | Tool examples | Alert threshold |
|--------|---------------|-----------------|
| Uptime | Pingdom, UptimeRobot | < 99.9% |
| APM | New Relic, Datadog, Scout | p95 > 2s |
| Errors | Sentry, Flare | > 10/min 5xx |
| Disk | Node exporter | > 85% |
| MySQL | PMM, slow query log | slow > 2s |
| Queue depth | Horizon / custom | > 100 jobs |
| SSL expiry | Certbot timer | < 14 days |

**Health check:** Laravel `/up` — monitor from internal network.

**Synthetic checks:** `/ar`, `/en/contact`, `/ik-admin/login` (200/302 only).

---

## 9. Logging strategy

| Channel | Path | Purpose |
|---------|------|---------|
| `daily` | `storage/logs/laravel.log` | Application (level `warning` prod) |
| `security` | `storage/logs/security.log` | Spam, auth anomalies (90 days) |
| Nginx | `/var/log/nginx/` | Access + error |
| PHP-FPM | syslog | Worker crashes |

**Do not log:** passwords, full credit cards, session IDs in info logs.

**PII in contact logs:** reference number only in security log; full data in DB with retention policy.

**Log rotation:** `logrotate` daily, compress, 30–90 day retention per compliance.

---

## 10. Production deployment checklist

### Pre-deploy
- [ ] Security review / pen test sign-off (annual for enterprise)
- [ ] `composer audit` clean
- [ ] All migrations tested on staging clone
- [ ] `.env` production values in secrets manager
- [ ] DNS + TLS certificates valid
- [ ] Redis/MySQL provisioned with encryption at rest

### Deploy
- [ ] Enable maintenance mode: `php artisan down --secret="token"`
- [ ] Pull release tag / deploy artifact
- [ ] `composer install --no-dev`
- [ ] `npm ci && npm run build`
- [ ] `php artisan migrate --force`
- [ ] `php artisan config:cache && route:cache && view:cache`
- [ ] Restart PHP-FPM + queue workers
- [ ] `php artisan up`

### Post-deploy smoke test
- [ ] `/` → `/ar` redirect
- [ ] Homepage sections load
- [ ] Contact form submits + queue processes
- [ ] `/ik-admin` login (change default password!)
- [ ] Upload project image in CMS
- [ ] Legacy redirect sample URL
- [ ] CSP no critical violations (browser console)

### Security sign-off
- [ ] `APP_DEBUG=false` verified (no stack traces)
- [ ] Security headers present (securityheaders.com scan)
- [ ] SSL Labs grade A or A+
- [ ] Admin password rotated; only named accounts active
- [ ] Backups verified within 24h

---

## Aramco-level enterprise readiness gaps (roadmap)

| Priority | Item |
|----------|------|
| P0 | Production `.env`, HTTPS, backups tested |
| P0 | Change default CMS credentials |
| P1 | Redis cache/session/queue |
| P1 | 2FA for admin users |
| P1 | WAF + DDoS protection |
| P2 | S3 for media with private ACL |
| P2 | SIEM integration |
| P2 | Annual penetration test |
| P3 | HA / multi-AZ database |
| P3 | Content Security Policy enforced (post report-only) |

---

## Files reference

| Path | Purpose |
|------|---------|
| `config/security.php` | CSP, headers, rate limits, upload limits |
| `app/Http/Middleware/SecurityHeaders.php` | Response headers |
| `app/Services/ContentCacheService.php` | Targeted cache busting |
| `app/Jobs/NotifyContactSubmission.php` | Queued contact alerts |
| `deploy/nginx/iksaudi.conf.example` | Nginx template |
| `database/migrations/2026_05_18_200000_add_production_performance_indexes.php` | DB indexes |

---

*Document version 1.0 — align with IK Saudi IT security policies before go-live.*
