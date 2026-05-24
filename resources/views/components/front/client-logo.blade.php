@props([
    'name',
    'url' => '#',
    'icon' => 'bi-building-fill',
    'image' => null,
])

<a href="{{ $url }}"
   class="client-logo-item glightbox"
   role="listitem"
   aria-label="{{ $name }}"
   @if ($url !== '#' && $url !== null) target="_blank" rel="noopener noreferrer" @endif>
    <div class="client-logo-inner">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $name }}" loading="lazy" style="max-width:100%;max-height:60px;object-fit:contain;">
        @else
            <i class="bi {{ $icon }} client-logo-icon" aria-hidden="true"></i>
            <span class="client-logo-name">{{ $name }}</span>
        @endif
    </div>
</a>
