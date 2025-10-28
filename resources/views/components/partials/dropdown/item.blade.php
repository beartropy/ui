@aware(['color' => 'neutral'])
@props([
    'icon' => null,
    'disabled' => false,
    'as' => 'button', // 'a' o 'button'
    'closeOnClick' => true,      // 👈 NUEVA PROP
    'color' => null
])

@php
    $preset = config("beartropyui.presets.dropdown.colors.{$color}")
            ?? config("beartropyui.presets.dropdown.colors.neutral");

    $bleed = '-my-1';

    $base = "flex items-center w-full px-4 py-2 text-sm transition-colors {$bleed}";
    $tone = "{$preset['item_hover_bg']} {$preset['item_active_bg']} {$preset['item_text_color']}";
    $classes = trim("$base $tone " . ($disabled ? 'opacity-50 cursor-not-allowed' : ''));

    // armamos el manejador de click según prop
    $closeAttr = $closeOnClick ? '@click="$dispatch(\'bt-dropdown-close\')"' : '';
@endphp

@if ($as === 'a')
    <a
        {{ $attributes->merge(['class' => "$classes first:rounded-t-md last:rounded-b-md", 'role' => 'menuitem']) }}
        {{ $disabled ? 'aria-disabled=true' : '' }}
        {!! $closeAttr !!}
    >
        @if ($icon)
            <x-beartropy-ui::icon name="{{ $icon }}" class="w-4 h-4 mr-2" />
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="button"
        {{ $attributes->merge(['class' => "$classes first:rounded-t-md last:rounded-b-md", 'role' => 'menuitem']) }}
        {{ $disabled ? 'disabled' : '' }}
        {!! $closeAttr !!}
    >
        @if ($icon)
            <x-beartropy-ui::icon name="{{ $icon }}" class="w-4 h-4 mr-2" />
        @endif
        {{ $slot }}
    </button>
@endif
