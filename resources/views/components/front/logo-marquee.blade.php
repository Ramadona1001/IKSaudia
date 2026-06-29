@props([
    'items',
    'ariaLabel' => '',
])

<div
    {{ $attributes->class('clients-marquee-wrap clients-marquee-wrap--standalone') }}
    role="region"
    @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
>
    <div class="clients-marquee">
        @foreach ($items as $item)
            <x-front.client-marquee-item
                :name="$item['name']"
                :image="$item['image'] ?? null"
                :url="$item['url'] ?? null"
            />
        @endforeach
        @foreach ($items as $item)
            <x-front.client-marquee-item
                :name="$item['name']"
                :image="$item['image'] ?? null"
                :url="$item['url'] ?? null"
                aria-hidden="true"
            />
        @endforeach
    </div>
</div>
