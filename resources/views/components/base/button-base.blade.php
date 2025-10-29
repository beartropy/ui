@php
    [$colorPreset, $sizePreset] = $getComponentPresets('button');
    $tag = $tag ?? ($href ? 'a' : 'button');
    $wireTarget = $getWireTarget();

@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    @if(!$href) type="{{ $type }}" @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge([
        'class' => "
            inline-flex items-center justify-center rounded-md border transition-colors relative text-sm tracking-wide
            {$sizePreset['font']} {$sizePreset['height']} {$sizePreset['px']} {$sizePreset['py']}
            {$colorPreset['bg']} {$colorPreset['text']} {$colorPreset['border']}
            {$colorPreset['hover']} {$colorPreset['focus']} {$colorPreset['active']}
            " . ($disabled ? $colorPreset['disabled'] : '')
    ]) }}
    @if($wireTarget) wire:target="{{ $wireTarget }}" wire:loading.attr="disabled" @endif
>



    @if($spinner && $wireTarget)
        <span
            wire:loading
            wire:target="{{$wireTarget}}"
            class="hidden absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center justify-center"
        >
             @include('beartropy-ui-svg::beartropy-spinner', [
                'class' => ($sizePreset['iconSize'] .' animate-spin text-current')
            ])
        </span>
    @endif

    <div class="inline-flex items-center justify-center" @if($spinner && $wireTarget) wire:loading.class="opacity-0" wire:target="{{$wireTarget}}" @endif>
        @if (isset($start))
            {{ $start }}
        @endif
        @if (isset($iconStart))
            <x-beartropy-ui::icon name="{{$iconStart}}" class="mr-2 {{$sizePreset['iconSize']}}" />
        @endif

        <span class="flex items-center whitespace-nowrap">
            {{ $slot }}
        </span>

        @if (isset($iconEnd))
            <x-beartropy-ui::icon name="{{$iconEnd}}" class="ml-2 {{$sizePreset['iconSize']}}" />
        @endif
        @if (isset($end))
            {{ $end }}
        @endif
    </div>
</{{ $tag }}>
