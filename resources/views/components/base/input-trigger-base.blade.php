@php
    [$colorPreset, $sizePreset, $shouldFill] = $getComponentPresets('input');
    $disabled = $disabled ?? false;
    $inputId = $attributes->get('id') ?? 'input-trigger-' . uniqid();

    $borderClass = $hasError
        ? ($colorPreset['border_error'] ?? $colorPreset['border'])
        : $colorPreset['border'];
    $ringClass = $hasError
        ? ($colorPreset['ring_error'] ?? $colorPreset['ring'])
        : $colorPreset['ring'];

@endphp

<div class="flex flex-col">

    <div
        {{ $attributes->merge(['tabindex' => 0])->class([
            'flex items-center w-full group transition-all shadow-sm rounded-lg outline-none overflow-hidden bt-trigger-base',
            $shouldFill ? $colorPreset['bg'] : 'bg-white dark:bg-gray-900',
            $borderClass,
            $ringClass,
            $disabled ? 'opacity-60 cursor-not-allowed' : '',
            $sizePreset['height'] ?? '',
            'min-w-0',
        ]) }}
        @if($disabled) aria-disabled="true" @endif
    >

        {{-- Start slot --}}
        @if (trim($start ?? ''))
            <div class="flex shrink-0 self-stretch beartropy-inputbase-start-slot">
                {{ $start }}
            </div>
        @endif

        {{-- Trigger button --}}
        @if (trim($button ?? ''))
            <div class="flex-1 flex items-center {{ $sizePreset['px'] ?? '' }} min-w-0">
                {{ $button }}
            </div>
        @endif

        {{-- End slot --}}
        @if (trim($end ?? ''))
            <div class="flex shrink-0 self-stretch beartropy-inputbase-end-slot">
                {{ $end }}
            </div>
        @endif
    </div>
    {{-- Dropdown slot: always after the trigger --}}
    @if (trim($dropdown ?? ''))
        <div class="relative">
            {{ $dropdown }}
        </div>
    @endif
</div>
