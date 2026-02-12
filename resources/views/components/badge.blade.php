@php
    [$colorPreset, $sizePreset] = $getComponentPresets('badge');
    $resolvedIconLeft = $icon ?? $iconLeft;
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-xl {$sizePreset['font']} {$sizePreset['px']} {$sizePreset['py']} {$colorPreset['bg']} {$colorPreset['text']} " . ($colorPreset['border'] ?? '')]) }}>
    @if(isset($start))
        {{ $start }}
    @endif
    @if($resolvedIconLeft)
        <x-beartropy-ui::icon :name="$resolvedIconLeft" class="mr-1 {{ $sizePreset['iconSize'] }}" />
    @endif

    {{ $label }}{{ $slot }}

    @if($iconRight)
        <x-beartropy-ui::icon :name="$iconRight" class="ml-1 {{ $sizePreset['iconSize'] }}" />
    @endif
    @if(isset($end))
        {{ $end }}
    @endif
</span>
