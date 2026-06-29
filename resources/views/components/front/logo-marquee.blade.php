@props([
    'items',
    'ariaLabel' => '',
    'imageOnly' => false,
])

@php
    $marqueeItems = collect($items);

    if ($imageOnly) {
        $marqueeItems = $marqueeItems->filter(fn (array $item) => filled($item['image'] ?? null))->values();
    }
@endphp

@if ($marqueeItems->isNotEmpty())
    <div
        {{ $attributes->class(['clients-marquee-wrap clients-marquee-wrap--standalone', 'clients-marquee-wrap--image-only' => $imageOnly]) }}
        role="region"
        @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    >
        <div class="clients-marquee">
            @foreach ($marqueeItems as $item)
                <x-front.client-marquee-item
                    :name="$item['name']"
                    :image="$item['image'] ?? null"
                    :url="$item['url'] ?? null"
                    :image-only="$imageOnly"
                />
            @endforeach
            @foreach ($marqueeItems as $item)
                <x-front.client-marquee-item
                    :name="$item['name']"
                    :image="$item['image'] ?? null"
                    :url="$item['url'] ?? null"
                    :image-only="$imageOnly"
                    aria-hidden="true"
                />
            @endforeach
        </div>
    </div>
@endif
