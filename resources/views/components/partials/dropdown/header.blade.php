@props([
    'muted' => true,
])

@php
    $base = 'px-4 py-1.5 text-[11px] uppercase tracking-wide font-semibold select-none';
    $tone = $muted
        ? 'text-gray-500 dark:text-gray-400'
        : 'text-gray-700 dark:text-gray-200';
@endphp

<div role="presentation" {{ $attributes->merge(['class' => "$base $tone"]) }}>
    {{ $slot }}
</div>
