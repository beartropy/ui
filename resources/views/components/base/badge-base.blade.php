@php

[$colorPreset, $sizePreset] = $getComponentPresets('badge',null,'sm');
@endphp

<span class="inline-flex items-center rounded-xl {{ $sizePreset['font'] }} {{ $sizePreset['px'] }} {{ $sizePreset['py'] }} {{ $colorPreset['bg'] }} {{ $colorPreset['text'] }} {{ $colorPreset['border'] ?? '' }}">
    @if(isset($start))
        {{ $start }}
    @endif
    @if($iconLeft)
        <x-bt-icon :name="$iconLeft" class="mr-1 {{ $sizePreset['iconSize'] }}" />
    @endif

    {{ $slot }}

    @if($iconRight)
        <x-bt-icon :name="$iconRight" class="ml-1 {{ $sizePreset['iconSize'] }}" />
    @endif
    @if(isset($end))
        {{ $end }}
    @endif
</span>
