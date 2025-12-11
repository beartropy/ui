@php
    [$colorPreset, $sizePreset] = $getComponentPresets('checkbox');
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

    // Determina el contenido del label: slot > prop
    $labelContent = trim($slot) !== '' ? $slot : $label;

@endphp

<div {{ $attributes->only(['class', 'style'])->merge(['class' => 'flex flex-col min-h-full justify-center']) }}>
    <label class="relative inline-flex items-center cursor-pointer select-none gap-1 {{ $disabled ? $colorPreset['disabled'] : '' }}">
        @if($labelPosition === 'left')
            <span class="{{ $sizePreset['font'] }} {{ $labelSpacing }} {{ $labelClass }}">
                {{ $labelContent }}
            </span>
        @endif

        <div class="relative">
            <input
                type="checkbox"
                {{ $attributes->except(['class', 'style'])->merge(['class' => 'peer sr-only', 'disabled' => $disabled, 'checked' => $checked, 'value' => $value]) }}
            >
            <span class="{{ $sizePreset['box'] }} {{ in_array($size ?? 'md', ['xs','sm', 'md']) ? 'rounded-sm' : 'rounded-md' }}
                        {{ $borderClass }}
                        {{ $colorPreset['bg'] ?? '' }}
                        {{ $colorPreset['hover'] ?? '' }}
                        {{ $focusClass }}
                        {{ $colorPreset['active'] ?? '' }}
                        transition {{ $colorPreset['checked'] ?? '' }}
                        items-center justify-center block">
            </span>
            <span class="{{ $sizePreset['box'] }} top-0 left-0 absolute text-white dark:text-neutral-900 scale-0 peer-checked:scale-100 transition pointer-events-none">
                <svg
                    viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M4 8l3 3 5-5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div>

        @if($labelPosition === 'right')
            <span class="{{ $sizePreset['font'] }} {{ $labelSpacing }} {{ $labelClass }}">
                {{ $labelContent }}
            </span>
        @endif
    </label>
    <x-beartropy-ui::support.field-help :error-message="$finalError" :hint="$hint ?? null" />
</div>
