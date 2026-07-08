@php
    use App\Support\PageHeroPattern;

    $brandPrimary = setting('branding.primary_color', '#060c1a') ?: '#060c1a';
    $brandSecondary = setting('branding.secondary_color', '#1a2d4a') ?: '#1a2d4a';
    $brandAccent = setting('branding.accent_color', '#c9a227') ?: '#c9a227';

    // Independent region colors — fall back to sensible defaults so changing
    // brand primary/secondary no longer forces header/footer to follow.
    $pageBg = setting('branding.page_bg_color', $brandPrimary) ?: $brandPrimary;
    $headerBg = setting('branding.header_bg_color', '#ffffff') ?: '#ffffff';
    $headerText = setting('branding.header_text_color', $brandPrimary) ?: $brandPrimary;
    $headerTextHover = setting('branding.header_text_hover_color', $brandSecondary) ?: $brandSecondary;
    $headerIconBg = setting('branding.header_icon_bg_color', $brandSecondary) ?: $brandSecondary;
    $heroText = setting('branding.hero_text_color', '#ffffff') ?: '#ffffff';
    $footerBg = setting('branding.footer_bg_color', '#030710') ?: '#030710';
    $footerText = setting('branding.footer_text_color', '#ffffff') ?: '#ffffff';
    $footerAccent = setting('branding.footer_accent_color', $brandAccent) ?: $brandAccent;

    $pageHeroPattern = PageHeroPattern::cssValue(
        setting('branding.page_hero_pattern', 'hexagon'),
        setting_url('branding.page_hero_pattern_image'),
        $brandAccent,
        (int) setting('branding.page_hero_pattern_size', 60),
    );
@endphp
<style id="brand-colors">
    :root,
    html {
        --brand-primary: {{ $brandPrimary }};
        --brand-secondary: {{ $brandSecondary }};
        --brand-accent: {{ $brandAccent }};

        --page-bg: {{ $pageBg }};
        --header-bg: {{ $headerBg }};
        --header-bg-scrolled: {{ $headerBg }};
        --header-text: {{ $headerText }};
        --header-text-hover: {{ $headerTextHover }};
        --header-border: color-mix(in srgb, {{ $brandAccent }} 15%, transparent);
        --header-icon-bg: {{ $headerIconBg }};
        --hero-text: {{ $heroText }};
        --footer-bg: {{ $footerBg }};
        --footer-text: {{ $footerText }};
        --footer-muted: #94a3b8;
        --footer-accent: {{ $footerAccent }};
        --footer-border: color-mix(in srgb, {{ $footerAccent }} 10%, transparent);

        --page-hero-pattern-image: {!! $pageHeroPattern['image'] !!};
        --page-hero-pattern-size: {{ $pageHeroPattern['size'] }};
    }
</style>
