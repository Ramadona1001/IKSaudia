@php
    use App\Support\PageHeroPattern;

    $brandPrimary = setting('branding.primary_color', '#060c1a') ?: '#060c1a';
    $brandSecondary = setting('branding.secondary_color', '#1a2d4a') ?: '#1a2d4a';
    $brandAccent = setting('branding.accent_color', '#c9a227') ?: '#c9a227';
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
        --page-hero-pattern-image: {!! $pageHeroPattern['image'] !!};
        --page-hero-pattern-size: {{ $pageHeroPattern['size'] }};
    }
</style>
