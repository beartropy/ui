@php
    [$colorPreset, $sizePreset, $presetNames] = $getComponentPresets('card', null);
@endphp

<div {{ $attributes->merge(['class' => $colorPreset['wrapper']]) }}>
    @if (!empty($title))
        <div class="{{ $colorPreset['title'] }}">
            {!! $title !!}
        </div>
    @endif

    <div class="{{ $colorPreset['slot'] }}">
        {!! $slot !!}
    </div>

    @if (!empty($footer))
        <div class="{{ $colorPreset['footer'] }}">
            {!! $footer !!}
        </div>
    @endif
</div>
