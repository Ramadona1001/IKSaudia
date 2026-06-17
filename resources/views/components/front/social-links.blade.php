@props([
    'links' => [],
    'buttonClass' => 'footer-social-btn',
])

@php
    use App\Support\SocialPlatform;

    $links = is_array($links) ? $links : [];
@endphp

@if (count($links))
    <div {{ $attributes->merge(['class' => 'footer-socials']) }} aria-label="{{ __('footer.socials') }}">
        @foreach ($links as $link)
            <a href="{{ $link['url'] ?? '#' }}"
               @class([$buttonClass])
               aria-label="{{ SocialPlatform::label($link) }}"
               target="_blank"
               rel="noopener noreferrer">
                <i class="bi {{ SocialPlatform::icon($link['platform'] ?? '') }}" aria-hidden="true"></i>
            </a>
        @endforeach
    </div>
@endif
