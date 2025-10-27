@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('dropdown');
    $dropdownId = $attributes->get('id') ?? 'dropdown-' . uniqid();
@endphp

<div class="relative {{ $attributes->get('class') }}">
    <div
        x-data="{ open: false }"
        class="relative inline-block"
        x-id="['dropdown-{{ $dropdownId }}']"
        @keydown.escape.window="open = false"
        @click.away="open = false"
        @bt-dropdown-close.window="open = false"
    >
        <!-- Trigger -->
        <div @click="open = !open" x-ref="trigger" aria-haspopup="true" :aria-expanded="open">
            {{ $trigger ?? '' }}
        </div>

        <!-- Panel -->
        <x-beartropy-ui::base.dropdown-base
            x-show="open"
            :autoFit="false"
            x-transition
            x-anchor="$refs.trigger"
            side="{{ $side }}"
            placement="{{ $placement }}"
            color="{{$presetNames['color']}}"
            role="menu"
            width="{{ $sizePreset['dropdownWidth'] }}"
        >
            <div class="py-1">
                {{ $slot }}
            </div>
        </x-beartropy-ui::base.dropdown-base>
    </div>
</div>
