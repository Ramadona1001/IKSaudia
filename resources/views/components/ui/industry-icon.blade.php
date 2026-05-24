@props(['icon' => 'default', 'class' => 'h-7 w-7'])

@php
    $icon = $icon ?? 'default';
@endphp

<svg {{ $attributes->merge(['class' => $class.' text-accent']) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
    @switch($icon)
        @case('oil-gas')
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M8 8h8M6 12h12M9 16h6"/>
            @break
        @case('mining')
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 20h16M8 20V9l4-4 4 4v11M12 5v15"/>
            @break
        @case('subsea')
            <path stroke-linecap="round" stroke-linejoin="round" d="M2 15c2-3 6-3 8 0s6 3 8 0M4 18c1-2 3-2 4 0M14 18c1-2 3-2 4 0"/>
            @break
        @case('petrochemical')
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3h6v4H9V3zM7 9h10v12H7V9zm3 3v6m4-6v6"/>
            @break
        @default
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
    @endswitch
</svg>
