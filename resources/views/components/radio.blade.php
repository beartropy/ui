@php

    [$colorPreset, $sizePreset] = $getComponentPresets('radio');
    $disabled = $attributes->get('disabled');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);

    $borderClass = $hasError
        ? ($colorPreset['border_error'] ?? $colorPreset['border'])
        : $colorPreset['border'];
    $focusClass = $hasError
        ? ($colorPreset['focus_error'] ?? $colorPreset['focus'])
        : $colorPreset['focus'];
    $labelClass = $hasError
        ? ($colorPreset['label_error'] ?? $colorPreset['label'])
        : $colorPreset['label'];
    $labelSpacing = $labelPosition === 'left'
        ? ($sizePreset['mr'] ?? 'mr-2')
        : ($sizePreset['ml'] ?? 'ml-2');

@endphp

<div class="flex flex-col min-h-full justify-center">
    <label class="inline-flex items-center cursor-pointer gap-1 relative select-none {{ $disabled ? $colorPreset['disabled'] : '' }}">
        @if($labelPosition === 'left')
            <span class="{{ $sizePreset['font'] }} {{ $labelSpacing }} {{ $labelClass }}">
                {{ trim($slot) !== '' ? $slot : $label }}
            </span>
        @endif

        <div class="relative">
            <input
                type="radio"
                {{ $attributes->merge(['class' => 'peer sr-only', 'disabled' => $disabled]) }}
            >
            <span class="{{ $sizePreset['box'] }} rounded-full
                {{ $borderClass }}
                {{ $colorPreset['bg'] ?? '' }}
                {{ $colorPreset['hover'] ?? '' }}
                {{ $focusClass }}
                {{ $colorPreset['active'] ?? '' }}
                transition {{ $colorPreset['checked'] ?? '' }}
                block"></span>
            <span class="absolute rounded-full
                {{ $colorPreset['dot'] ?? 'bg-white dark:bg-black' }}
                left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2
                scale-0 peer-checked:scale-100 transition pointer-events-none {{ $sizePreset['dot'] }}"></span>
        </div>

        @if($labelPosition !== 'left')
            <span class="{{ $sizePreset['font'] }} {{ $labelSpacing }} {{ $labelClass }}">
                {{ trim($slot) !== '' ? $slot : $label }}
            </span>
        @endif
    </label>
    @if(!$grouped)
        <x-beartropy-ui::support.field-help :error-message="$finalError" :hint="$hint ?? null" />
    @endif
</div>
