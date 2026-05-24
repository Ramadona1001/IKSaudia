@props(['items' => []])

@php $locale = app()->getLocale(); @endphp

<nav aria-label="{{ __('common.breadcrumb') }}" class="text-sm">
    <ol class="flex flex-wrap items-center gap-2 text-steel-400">
        <li>
            <a href="{{ route('home', $locale) }}" class="hover:text-accent transition">{{ __('navigation.home') }}</a>
        </li>
        @foreach ($items as $item)
            <li class="flex items-center gap-2">
                <span aria-hidden="true" class="text-steel-600">/</span>
                @if (! empty($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-accent transition">{{ $item['label'] }}</a>
                @else
                    <span class="text-steel-200" aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
