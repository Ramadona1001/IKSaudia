@props([
    'title' => null,
    'description' => null,
    'background' => 'bg-dark2',
])

<section class="section-pad-sm {{ $background }}">
    <div class="container text-center" data-aos="fade-up">
        @if ($title)<h2 class="section-title mb-3">{{ $title }}</h2>@endif
        @if ($description)<p class="section-desc mx-auto mb-4">{{ $description }}</p>@endif
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            {{ $slot }}
        </div>
    </div>
</section>
