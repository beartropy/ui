@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('button-icon');
    $icon = $icon ?? 'plus';
    $label = $label ?? __('beartropy-ui::ui.new');
    $isLink = $attributes->has('href');

    $tag = $isLink ? 'a' : 'button';

    $wireTarget = $attributes->get('wire:target') ?? null;

    if ($spinner && is_null($wireTarget)) {
        $wireTarget = $attributes->get('wire:click') ?? null;
    }

    $rounded ??= 'full';

@endphp

<div class="relative">
    <{{ $tag }}
        {{ $attributes->merge([
            'class' =>
                'flex items-center justify-center cursor-pointer rounded-' .
                $rounded .
                ' shadow-lg transition ' .
                $colorPreset['bg'] .
                ' ' .
                $colorPreset['text'] .
                ' ' .
                $colorPreset['bg_hover'] .
                ' ' .
                $sizePreset['buttonIcon'],
            'aria-label' => $label,
        ]) }}
        @if ($spinner && $wireTarget && !$attributes->has('wire:target'))
            wire:target="{{ $wireTarget }}"
        @endif
    >

        @if ($spinner && $wireTarget)
            <div wire:loading wire:target="{{ $wireTarget }}">
                @include('beartropy-ui-svg::beartropy-spinner', [
                    'class' => $sizePreset['buttonIconIcon'] . ' animate-spin',
                    'tabindex' => '-1',
                ])
            </div>
            <div wire:loading.remove wire:target="{{ $wireTarget }}">
                @if ($slot->isNotEmpty())
                    {!! $slot !!}
                @else
                    <x-beartropy-ui::icon name="{{ $icon }}" class="{{ $sizePreset['buttonIconIcon'] }}"
                        set="{{ $iconSet }}" variant="{{ $iconVariant }}" />
                @endif
            </div>
        @else
            @if ($slot->isNotEmpty())
                {!! $slot !!}
            @else
                <x-beartropy-ui::icon name="{{ $icon }}" class="{{ $sizePreset['buttonIconIcon'] }}"
                    set="{{ $iconSet }}" variant="{{ $iconVariant }}" />
            @endif
        @endif
    </{{ $tag }}>
</div>
