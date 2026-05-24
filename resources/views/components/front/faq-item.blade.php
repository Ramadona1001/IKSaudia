@props([
    'question',
    'answer',
    'open' => false,
])

<div class="faq-item {{ $open ? 'open' : '' }}">
    <button class="faq-question" type="button" aria-expanded="{{ $open ? 'true' : 'false' }}">
        <span>{{ $question }}</span>
        <span class="faq-icon"><i class="bi bi-plus-lg" aria-hidden="true"></i></span>
    </button>
    <div class="faq-answer">
        <div class="faq-answer-inner">{!! safe_html($answer) !!}</div>
    </div>
</div>
