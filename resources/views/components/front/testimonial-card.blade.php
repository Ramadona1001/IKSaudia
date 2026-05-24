@props([
    'text',
    'name',
    'role' => null,
    'company' => null,
    'avatar' => null,
    'stars' => 5,
])

<div class="testimonial-card">
    <div class="testimonial-quote-icon" aria-hidden="true">"</div>
    <div class="testimonial-stars" aria-label="{{ $stars }} stars">
        @for ($i = 0; $i < (int) $stars; $i++)
            <i class="bi bi-star-fill" aria-hidden="true"></i>
        @endfor
    </div>
    <p class="testimonial-text">{{ $text }}</p>
    <div class="testimonial-author">
        <div class="testimonial-avatar" aria-hidden="true">
            @if ($avatar)
                <img src="{{ $avatar }}" alt="{{ $name }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
            @else
                <i class="bi bi-person-fill"></i>
            @endif
        </div>
        <div class="testimonial-author-info">
            <div class="name">{{ $name }}</div>
            @if ($role)<div class="role">{{ $role }}</div>@endif
            @if ($company)<div class="company">{{ $company }}</div>@endif
        </div>
    </div>
</div>
