@aware(['color' => 'neutral', 'variant' => 'ghost'])
@props([
    'icon' => null,
    'disabled' => false,
    'as' => 'button', // 'a' o 'button'
    'closeOnClick' => true,      // 👈 NUEVA PROP
])

@php
    $preset = config("beartropyui.presets.dropdown.colors.{$color}")
            ?? config("beartropyui.presets.dropdown.colors.neutral");

    $base = 'flex items-center w-full px-4 py-2 text-sm transition-colors';
    $tone = "{$preset['item_hover_bg']} {$preset['item_active_bg']} {$preset['item_text_color']}";
    $classes = trim("$base $tone " . ($disabled ? 'opacity-50 cursor-not-allowed' : ''));

    // armamos el manejador de click según prop
    $closeAttr = $closeOnClick ? '@click="$dispatch(\'bt-dropdown-close\')"' : '';
@endphp

@if ($as === 'a')
    <a
        {{ $attributes->merge(['class' => $classes, 'role' => 'menuitem']) }}
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
        {{ $attributes->merge(['class' => $classes, 'role' => 'menuitem']) }}
        {{ $disabled ? 'disabled' : '' }}
        {!! $closeAttr !!}
    >
        @if ($icon)
            <x-beartropy-ui::icon name="{{ $icon }}" class="w-4 h-4 mr-2" />
        @endif
        {{ $slot }}
    </button>
@endif
