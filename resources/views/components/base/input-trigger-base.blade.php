@php
    [$colorPreset, $sizePreset] = $getComponentPresets('input');
    $disabled = $attributes->get('disabled');
    $inputId = $attributes->get('id') ?? 'input-trigger-' . uniqid();

    $borderClass = $hasError
        ? ($colorPreset['border_error'] ?? $colorPreset['border'])
        : $colorPreset['border'];
    $ringClass = $hasError
        ? ($colorPreset['ring_error'] ?? $colorPreset['ring'])
        : $colorPreset['ring'];

@endphp

<div class="flex flex-col {{ $disabled ? $colorPreset['disabled'] : '' }}">

    <div class="flex items-center w-full group transition-all shadow-sm rounded
        {{ $colorPreset['bg'] ?? '' }}
        {{ $borderClass }}
        {{ $ringClass }}
        {{ $colorPreset['disabled_bg'] ?? '' }}
        {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}
        {{ $sizePreset['px'] ?? '' }}
        {{ $sizePreset['height'] ?? '' }}
        min-w-0"
        tabindex="0"
    >

        {{-- Start slot --}}
        @if (trim($start ?? ''))
            <div class="flex items-center space-x-2 pr-2 min-w-0">
                {{ $start }}
            </div>
        @endif

        {{-- Botón trigger --}}
        @if (trim($button ?? ''))
            {{ $button }}
        @endif

        {{-- End slot --}}
        @if (trim($end ?? ''))
            <div class="flex items-center pl-2 min-w-0">
                {{ $end }}
            </div>
        @endif
    </div>
    {{-- DROPDOWN SLOT: siempre después del trigger --}}
    @if (trim($dropdown ?? ''))
        <div class="relative">
            {{ $dropdown }}
        </div>
    @endif
</div>
