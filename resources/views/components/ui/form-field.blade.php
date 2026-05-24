@props([
    'label',
    'name',
    'type' => 'text',
    'required' => false,
])

@php
    $id = $name;
    $hasError = $errors->has($name);
@endphp

<div>
    <label for="{{ $id }}" class="mb-2 block text-sm font-medium text-steel-200">
        {{ $label }}
        @if ($required)
            <span class="text-accent" aria-hidden="true">*</span>
        @endif
    </label>

    @if ($type === 'textarea')
        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            rows="5"
            @if ($required) required @endif
            {{ $attributes->merge(['class' => 'w-full rounded-lg border bg-navy-950/50 px-4 py-3 text-white placeholder-steel-500 transition focus:border-accent focus:ring-1 focus:ring-accent '.($hasError ? 'border-red-500' : 'border-white/15')]) }}
        >{{ old($name) }}</textarea>
    @else
        <input
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name) }}"
            @if ($required) required @endif
            {{ $attributes->merge(['class' => 'w-full rounded-lg border bg-navy-950/50 px-4 py-3 text-white placeholder-steel-500 transition focus:border-accent focus:ring-1 focus:ring-accent '.($hasError ? 'border-red-500' : 'border-white/15')]) }}
        >
    @endif

    @error($name)
        <p class="mt-1.5 text-sm text-red-400" role="alert">{{ $message }}</p>
    @enderror
</div>
