@props(['class' => 'h-4 w-full'])

<div {{ $attributes->merge(['class' => 'skeleton '.$class]) }} aria-hidden="true" role="presentation"></div>
