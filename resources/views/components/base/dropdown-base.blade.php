@php
    [$colorPreset, $sizePreset] = $getComponentPresets($presetFor ?? 'dropdown');

    $alignment = match($placement) {
        'right' => 'right-0',
        'center' => 'left-1/2 -translate-x-1/2',
        default => 'left-0',
    };
    $vertical = $side === 'top'
        ? 'bottom-full mb-1'
        : 'top-full mt-1';

@endphp

<div
    x-show="open"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="absolute z-50 {{ $alignment }} {{ $vertical }} {{ $width }}
        rounded-lg
        {{ $colorPreset['dropdown_border'] }}
        {{ $colorPreset['dropdown_bg'] }}
        {{ $colorPreset['dropdown_shadow'] }}
        origin-top beartropy-thin-scrollbar"
    style="min-width: 8rem;"
    @click.away="typeof onDropdownClose === 'function' ? onDropdownClose() : open = false"
{{--     {{ $attributes }} --}}
>
    {{ $slot }}
</div>
