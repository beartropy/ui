@php
    [$colorPreset, $sizePreset] = $getComponentPresets('toggle');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);

    $labelClass = $hasError
        ? ($colorPreset['label_error'] ?? $colorPreset['label'])
        : $colorPreset['label'];

    $positions = [
        'top'    => 'flex flex-col gap-1',
        'bottom' => 'flex flex-col gap-1',
        'left'   => 'inline-flex items-center gap-2',
        'right'  => 'inline-flex items-center gap-2',
    ];
    $layoutClasses = $positions[$labelPosition] ?? $positions['right'];

    $trackClass = implode(' ', [
        $sizePreset['trackWidth'] ?? '',
        $sizePreset['trackHeight'] ?? '',
        'rounded-full transition relative block',
        $colorPreset['bg'] ?? '',
        $colorPreset['border'] ?? '',
        $colorPreset['checked'] ?? '',
        $colorPreset['hover'] ?? '',
        $hasError ? ($colorPreset['border_error'] ?? '') : '',
        $hasError ? ($colorPreset['focus_error'] ?? $colorPreset['focus'] ?? '') : ($colorPreset['focus'] ?? ''),
        $colorPreset['active'] ?? '',
    ]);
    $inputId = $attributes->get('id') ?? ('beartropy-toggle-' . uniqid());

@endphp

<div class="">
    <div class="{{ $layoutClasses }} min-h-full justify-center">
        @if($labelPosition === 'top')
            <label
                for="{{ $inputId }}"
                class="{{ $sizePreset['font'] }} {{ $labelClass }} cursor-pointer select-none"
            >
                @if (trim($slot))
                    {{ $slot }}
                @elseif ($label)
                    {{ $label }}
                @endif
            </label>
        @endif

        <label class="inline-flex items-center cursor-pointer select-none gap-2 relative {{ $disabled ? $colorPreset['disabled'] : '' }}">
            @if($labelPosition === 'left')
                <span class="{{ $sizePreset['font'] }} {{ $labelClass }}">
                    @if (trim($slot))
                        {{ $slot }}
                    @elseif ($label)
                        {{ $label }}
                    @endif
                </span>
            @endif
            <div class="relative">
                <input
                    id="{{ $inputId }}"
                    type="checkbox"
                    {{ $disabled === true ? 'disabled' : '' }}
                    {{ $attributes->merge(['class' => 'peer sr-only', 'id' => $inputId]) }}

                >
                <span class="{{ $trackClass }}"></span>
                <span class="absolute transition rounded-full
                    {{ $sizePreset['thumb'] }}
                    {{ $sizePreset['thumbTop'] ?? 'top-1' }}
                    {{ $sizePreset['thumbLeft'] ?? 'left-1' }}
                    {{ $colorPreset['thumb'] ?? '' }}
                    {{ $sizePreset['thumbTranslate'] }}"></span>
            </div>
            @if($labelPosition === 'right')
                <span class="{{ $sizePreset['font'] }} {{ $labelClass }}">
                    @if (trim($slot))
                        {{ $slot }}
                    @elseif ($label)
                        {{ $label }}
                    @endif
                </span>
            @endif
        </label>

        @if($labelPosition === 'bottom')
            <label
                for="{{ $inputId }}"
                class="{{ $sizePreset['font'] }} {{ $labelClass }} cursor-pointer select-none"
            >
                @if (trim($slot))
                    {{ $slot }}
                @elseif ($label)
                    {{ $label }}
                @endif
            </label>
        @endif
    </div>
    <x-beartropy-ui::support.field-help :error-message="$finalError" :hint="$hint" />
</div>
