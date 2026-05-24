@php
    $brandPrimary = setting('branding.primary_color', '#060c1a') ?: '#060c1a';
    $brandSecondary = setting('branding.secondary_color', '#1a2d4a') ?: '#1a2d4a';
    $brandAccent = setting('branding.accent_color', '#c9a227') ?: '#c9a227';
@endphp
<style id="brand-colors">
    :root,
    html {
        --brand-primary: {{ $brandPrimary }};
        --brand-secondary: {{ $brandSecondary }};
        --brand-accent: {{ $brandAccent }};
    }
</style>
