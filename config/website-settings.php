<?php

/**
 * Website settings registry.
 *
 * Keys use dot notation (e.g. general.site_name) and map to form state paths.
 * Translatable keys store per-locale values in site_setting_translations.
 */
return [

    'cache' => [
        'enabled' => (bool) env('SITE_SETTINGS_CACHE_ENABLED', true),
        'prefix' => 'site_settings',
        'ttl' => (int) env('SITE_SETTINGS_CACHE_TTL', 3600),
        'version' => 1,
    ],

    'locales' => config('locales.supported', ['ar', 'en']),

    'groups' => [
        'general' => ['label' => 'General', 'icon' => 'heroicon-o-cog-6-tooth', 'sort' => 10],
        'branding' => ['label' => 'Branding', 'icon' => 'heroicon-o-swatch', 'sort' => 20],
        'footer' => ['label' => 'Footer', 'icon' => 'heroicon-o-bars-3-bottom-left', 'sort' => 30],
        'homepage' => ['label' => 'Homepage', 'icon' => 'heroicon-o-home', 'sort' => 35],
        'contact' => ['label' => 'Contact', 'icon' => 'heroicon-o-phone', 'sort' => 40],
        'social' => ['label' => 'Social Media', 'icon' => 'heroicon-o-share', 'sort' => 50],
        'newsletter' => ['label' => 'Newsletter', 'icon' => 'heroicon-o-envelope-open', 'sort' => 60],
        'seo' => ['label' => 'SEO & Analytics', 'icon' => 'heroicon-o-magnifying-glass', 'sort' => 70],
        'advanced' => ['label' => 'Advanced', 'icon' => 'heroicon-o-wrench-screwdriver', 'sort' => 80],
    ],

    'definitions' => [

        // ── General ──────────────────────────────────────────────────────
        'general.site_name' => ['group' => 'general', 'type' => 'text', 'translatable' => true, 'label' => 'Website name'],
        'general.site_tagline' => ['group' => 'general', 'type' => 'text', 'translatable' => true, 'label' => 'Tagline'],
        'general.default_locale' => ['group' => 'general', 'type' => 'text', 'translatable' => false, 'label' => 'Default locale'],
        'general.supported_locales' => ['group' => 'general', 'type' => 'json', 'translatable' => false, 'label' => 'Supported locales'],
        'general.favicon' => ['group' => 'general', 'type' => 'image', 'translatable' => false, 'label' => 'Favicon'],
        'general.logo' => ['group' => 'general', 'type' => 'image', 'translatable' => false, 'label' => 'Main logo'],
        'general.logo_sticky' => ['group' => 'general', 'type' => 'image', 'translatable' => false, 'label' => 'Sticky header logo'],
        'general.logo_footer' => ['group' => 'general', 'type' => 'image', 'translatable' => false, 'label' => 'Footer logo'],
        'general.logo_dark' => ['group' => 'general', 'type' => 'image', 'translatable' => false, 'label' => 'Dark mode logo'],
        'general.seo_default_image' => ['group' => 'general', 'type' => 'image', 'translatable' => false, 'label' => 'Default SEO image'],
        'general.cookie_consent_enabled' => ['group' => 'general', 'type' => 'boolean', 'translatable' => false, 'label' => 'Cookie consent banner enabled'],
        'general.cookie_consent_message' => ['group' => 'general', 'type' => 'textarea', 'translatable' => true, 'label' => 'Cookie consent message'],
        'general.cookie_consent_accept_label' => ['group' => 'general', 'type' => 'text', 'translatable' => true, 'label' => 'Cookie accept button label'],
        'general.cookie_consent_policy_url' => ['group' => 'general', 'type' => 'text', 'translatable' => false, 'label' => 'Cookie policy URL (optional)'],

        // ── Branding ─────────────────────────────────────────────────────
        'branding.primary_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Primary color'],
        'branding.secondary_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Secondary color'],
        'branding.accent_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Accent color'],
        'branding.page_bg_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Page background color'],
        'branding.hero_text_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Hero text color'],
        'branding.header_bg_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Header background color'],
        'branding.header_text_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Header nav link color'],
        'branding.header_text_hover_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Header nav link hover color'],
        'branding.header_icon_bg_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Header icon background color'],
        'branding.footer_bg_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Footer background color'],
        'branding.footer_text_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Footer text color'],
        'branding.footer_accent_color' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Footer accent color'],
        'branding.font_latin' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Latin font family'],
        'branding.font_arabic' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Arabic font family'],
        'branding.hero_background_image' => ['group' => 'branding', 'type' => 'image', 'translatable' => false, 'label' => 'Hero background image'],
        'branding.hero_background_video' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Hero background video URL'],
        'branding.page_hero_pattern' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Inner page hero pattern'],
        'branding.page_hero_pattern_image' => ['group' => 'branding', 'type' => 'image', 'translatable' => false, 'label' => 'Custom page hero pattern image'],
        'branding.page_hero_pattern_size' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Page hero pattern tile size'],
        'branding.page_hero_pattern_opacity' => ['group' => 'branding', 'type' => 'text', 'translatable' => false, 'label' => 'Site pattern overlay opacity'],
        'branding.loading_logo' => ['group' => 'branding', 'type' => 'image', 'translatable' => false, 'label' => 'Loading screen logo'],

        // ── Footer ───────────────────────────────────────────────────────
        'footer.description' => ['group' => 'footer', 'type' => 'textarea', 'translatable' => true, 'label' => 'Footer description'],
        'footer.copyright' => ['group' => 'footer', 'type' => 'text', 'translatable' => true, 'label' => 'Copyright text'],
        'footer.quick_links' => ['group' => 'footer', 'type' => 'json', 'translatable' => false, 'label' => 'Quick links'],
        'footer.service_links' => ['group' => 'footer', 'type' => 'json', 'translatable' => false, 'label' => 'Service links'],
        'footer.industry_links' => ['group' => 'footer', 'type' => 'json', 'translatable' => false, 'label' => 'Industry links'],
        'footer.legal_links' => ['group' => 'footer', 'type' => 'json', 'translatable' => false, 'label' => 'Legal links'],
        'footer.certification_badges' => ['group' => 'footer', 'type' => 'json', 'translatable' => false, 'label' => 'Certification badges'],
        'footer.cta_enabled' => ['group' => 'footer', 'type' => 'boolean', 'translatable' => false, 'label' => 'Footer CTA enabled'],
        'footer.cta_overline' => ['group' => 'footer', 'type' => 'text', 'translatable' => true, 'label' => 'Footer CTA overline'],
        'footer.cta_title' => ['group' => 'footer', 'type' => 'text', 'translatable' => true, 'label' => 'Footer CTA title'],
        'footer.cta_subtitle' => ['group' => 'footer', 'type' => 'textarea', 'translatable' => true, 'label' => 'Footer CTA subtitle'],
        'footer.background_image' => ['group' => 'footer', 'type' => 'image', 'translatable' => false, 'label' => 'Footer background image'],

        // ── Homepage ─────────────────────────────────────────────────────
        'homepage.section_headings' => ['group' => 'homepage', 'type' => 'json', 'translatable' => false, 'label' => 'Homepage section headings'],

        // ── Contact ──────────────────────────────────────────────────────
        'contact.address' => ['group' => 'contact', 'type' => 'textarea', 'translatable' => true, 'label' => 'Address'],
        'contact.location_title' => ['group' => 'contact', 'type' => 'text', 'translatable' => true, 'label' => 'Location title (map card)'],
        'contact.maps_url' => ['group' => 'contact', 'type' => 'text', 'translatable' => false, 'label' => 'Google Maps URL'],
        'contact.maps_embed' => ['group' => 'contact', 'type' => 'textarea', 'translatable' => false, 'label' => 'Google Maps embed HTML'],
        'contact.phones' => ['group' => 'contact', 'type' => 'json', 'translatable' => false, 'label' => 'Phone numbers'],
        'contact.whatsapp' => ['group' => 'contact', 'type' => 'text', 'translatable' => false, 'label' => 'WhatsApp number'],
        'contact.emails' => ['group' => 'contact', 'type' => 'json', 'translatable' => false, 'label' => 'Email addresses'],
        'contact.working_hours' => ['group' => 'contact', 'type' => 'textarea', 'translatable' => true, 'label' => 'Working hours'],
        'contact.emergency_phone' => ['group' => 'contact', 'type' => 'text', 'translatable' => false, 'label' => 'Emergency contact'],
        'contact.form_recipients' => ['group' => 'contact', 'type' => 'json', 'translatable' => false, 'label' => 'Contact form recipients'],
        'contact.form_eyebrow' => ['group' => 'contact', 'type' => 'text', 'translatable' => true, 'label' => 'Contact form overline'],
        'contact.form_title' => ['group' => 'contact', 'type' => 'text', 'translatable' => true, 'label' => 'Contact form title'],
        'contact.form_title_accent' => ['group' => 'contact', 'type' => 'text', 'translatable' => true, 'label' => 'Contact form title accent'],
        'contact.form_intro' => ['group' => 'contact', 'type' => 'textarea', 'translatable' => true, 'label' => 'Contact form intro'],
        'contact.form_fields' => ['group' => 'contact', 'type' => 'json', 'translatable' => false, 'label' => 'Contact form fields'],

        // ── Social ───────────────────────────────────────────────────────
        'social.links' => ['group' => 'social', 'type' => 'json', 'translatable' => false, 'label' => 'Social links'],

        // ── Newsletter ───────────────────────────────────────────────────
        'newsletter.enabled' => ['group' => 'newsletter', 'type' => 'boolean', 'translatable' => false, 'label' => 'Newsletter enabled'],
        'newsletter.title' => ['group' => 'newsletter', 'type' => 'text', 'translatable' => true, 'label' => 'Newsletter title'],
        'newsletter.description' => ['group' => 'newsletter', 'type' => 'textarea', 'translatable' => true, 'label' => 'Newsletter description'],
        'newsletter.mailchimp_api_key' => ['group' => 'newsletter', 'type' => 'text', 'translatable' => false, 'label' => 'Mailchimp API key'],
        'newsletter.mailchimp_list_id' => ['group' => 'newsletter', 'type' => 'text', 'translatable' => false, 'label' => 'Mailchimp list ID'],
        'newsletter.mailchimp_server' => ['group' => 'newsletter', 'type' => 'text', 'translatable' => false, 'label' => 'Mailchimp server prefix'],
        'newsletter.cta_text' => ['group' => 'newsletter', 'type' => 'text', 'translatable' => true, 'label' => 'Newsletter CTA text'],

        // ── SEO ──────────────────────────────────────────────────────────
        'seo.default_meta_title' => ['group' => 'seo', 'type' => 'text', 'translatable' => true, 'label' => 'Default meta title'],
        'seo.default_meta_description' => ['group' => 'seo', 'type' => 'textarea', 'translatable' => true, 'label' => 'Default meta description'],
        'seo.default_keywords' => ['group' => 'seo', 'type' => 'text', 'translatable' => true, 'label' => 'Default keywords'],
        'seo.og_image' => ['group' => 'seo', 'type' => 'image', 'translatable' => false, 'label' => 'OpenGraph image'],
        'seo.twitter_card' => ['group' => 'seo', 'type' => 'text', 'translatable' => false, 'label' => 'Twitter card type'],
        'seo.twitter_site' => ['group' => 'seo', 'type' => 'text', 'translatable' => false, 'label' => 'Twitter @site'],
        'seo.robots' => ['group' => 'seo', 'type' => 'text', 'translatable' => false, 'label' => 'Robots meta'],
        'seo.google_analytics_id' => ['group' => 'seo', 'type' => 'text', 'translatable' => false, 'label' => 'Google Analytics ID'],
        'seo.google_tag_manager_id' => ['group' => 'seo', 'type' => 'text', 'translatable' => false, 'label' => 'Google Tag Manager ID'],
        'seo.meta_pixel_id' => ['group' => 'seo', 'type' => 'text', 'translatable' => false, 'label' => 'Meta Pixel ID'],
        'seo.schema_organization' => ['group' => 'seo', 'type' => 'json', 'translatable' => false, 'label' => 'Schema.org organization'],

        // ── Advanced ─────────────────────────────────────────────────────
        'advanced.maintenance_enabled' => ['group' => 'advanced', 'type' => 'boolean', 'translatable' => false, 'label' => 'Maintenance mode'],
        'advanced.maintenance_message' => ['group' => 'advanced', 'type' => 'textarea', 'translatable' => true, 'label' => 'Maintenance message'],
        'advanced.not_found_message' => ['group' => 'advanced', 'type' => 'textarea', 'translatable' => true, 'label' => '404 page message'],
        'advanced.smtp_host' => ['group' => 'advanced', 'type' => 'text', 'translatable' => false, 'label' => 'SMTP host (reference)'],
        'advanced.smtp_port' => ['group' => 'advanced', 'type' => 'text', 'translatable' => false, 'label' => 'SMTP port (reference)'],
        'advanced.smtp_encryption' => ['group' => 'advanced', 'type' => 'text', 'translatable' => false, 'label' => 'SMTP encryption (reference)'],
        'advanced.cache_enabled' => ['group' => 'advanced', 'type' => 'boolean', 'translatable' => false, 'label' => 'Settings cache enabled'],
    ],
];
