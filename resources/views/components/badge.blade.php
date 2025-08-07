@php
    [$colorPreset, $sizePreset, $presetNames] = $getComponentPresets('badge', null, 'sm');
@endphp
<span {{ $attributes->merge(['class' => $class ?? '']) }}>
    <x-beartropy-ui::base.badge-base
        :color="$presetNames['color']"
        :size="$presetNames['size']"
        :variant="$presetNames['variant']"
        :icon-left="$iconLeft"
        :icon-right="$iconRight"
    >
        {{ $slot }}
        @if(isset($start))
            <x-slot:start>
                {{ $start }}
            </x-slot:start>
        @endif
        @if(isset($end))
            <x-slot:end>
                {{ $end }}
            </x-slot:end>
        @endif
    </x-beartropy-ui::base.badge-base>
</span>
