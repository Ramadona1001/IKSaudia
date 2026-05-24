-- =============================================================================
-- IK Saudi Corporate CMS — MySQL 8 Reference Schema
-- Source of truth: database/migrations/*.php
-- Run: php artisan migrate (+ vendor publishes for Spatie packages)
-- =============================================================================

-- ─── LOCALIZATION ───────────────────────────────────────────────────────────
-- locales
-- site_settings, site_setting_translations

-- ─── NAVIGATION ─────────────────────────────────────────────────────────────
-- menus, menu_items, menu_item_translations

-- ─── PAGES & BLOCKS ───────────────────────────────────────────────────────────
-- pages, page_translations, page_blocks, page_block_translations

-- ─── SEO & REDIRECTS ──────────────────────────────────────────────────────────
-- seo_meta (polymorphic + locale)
-- redirects

-- ─── CONTENT MODULES ──────────────────────────────────────────────────────────
-- industries, industry_translations
-- services, service_translations, industry_service
-- projects, project_translations, industry_project, project_service
-- certifications, certification_translations
-- clients, client_translations
-- partners, partner_translations
-- news_posts, news_post_translations
-- tags, tag_translations, taggables

-- ─── CAREERS ──────────────────────────────────────────────────────────────────
-- careers, career_translations, career_applications

-- ─── HOMEPAGE BUILDER ─────────────────────────────────────────────────────────
-- home_sections, home_section_translations
-- home_section_items, home_section_item_translations

-- ─── LEADS ────────────────────────────────────────────────────────────────────
-- contact_submissions, newsletter_subscribers

-- ─── AUTH (Laravel + extension) ───────────────────────────────────────────────
-- users (+ is_active, 2FA, last_login, locale) — migration 000018
-- password_reset_tokens, sessions (Laravel default)

-- ─── VENDOR (publish separately) ──────────────────────────────────────────────
-- permissions, roles, model_has_permissions, model_has_roles, role_has_permissions
-- media, media conversions
-- activity_log

-- =============================================================================
-- ER SUMMARY
-- =============================================================================
--
--  [Parent] 1──* [parent]_translations (locale, slug, content)
--  [Entity] 1──1 seo_meta (per locale via unique morph+locale)
--  [Entity] *──* pivot tables (industry_service, industry_project, project_service)
--  [Entity] *──* tags via taggables (morph)
--  home_section_items ──morph──> Service|Project|Client|etc.
--  menu_items ──morph──> linkable OR custom url
--
