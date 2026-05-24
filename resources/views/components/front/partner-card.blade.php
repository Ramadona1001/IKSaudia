@props([
    'name',
    'type' => null,
    'icon' => 'bi-cpu-fill',
    'url' => '#',
    'image' => null,
])

<a href="{{ $url }}"
   class="partner-card"
   aria-label="{{ $name }}"
   @if ($url !== '#' && $url !== null) target="_blank" rel="noopener noreferrer" @endif>
    <div class="partner-card-logo">
        @if ($image)
            <img src="{{ $image }}" alt="" loading="lazy" width="160" height="72">
        @else
            <div class="partner-icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></div>
        @endif
    </div>
    <div class="partner-card-body">
        <div class="partner-name">{{ $name }}</div>
        @if ($type)
            <p class="partner-type">{{ $type }}</p>
        @endif
    </div>
</a>
