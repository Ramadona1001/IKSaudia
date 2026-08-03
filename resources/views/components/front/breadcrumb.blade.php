@props([
    'items' => [],
])

@php
    $locale = app()->getLocale();
@endphp

<div class="breadcrumb-wrap" aria-label="{{ __('common.breadcrumb') }}">
    <div class="container">
        <ol class="custom-breadcrumb">
            <li>
                <a href="{{ route('home', $locale) }}">
                    <i class="bi bi-house-fill" aria-hidden="true"></i>
                    {{ __('navigation.home') }}
                </a>
            </li>
            @foreach ($items as $item)
                @php
                    $label = $item['label'] ?? (
                        isset($item['section'])
                            ? \App\Support\HomeSectionHeading::breadcrumbLabel($item['section'], $locale)
                            : ''
                    );
                @endphp
                @if (! empty($item['url']) && ! $loop->last)
                    <li><a href="{{ $item['url'] }}">{{ $label }}</a></li>
                @else
                    <li class="active" aria-current="page">{{ $label }}</li>
                @endif
            @endforeach
        </ol>
    </div>
</div>
