@php
    [$colorPreset, $sizePreset] = $getComponentPresets('toggle');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $autosaveKey = $autosaveKey ?? ($wireModelValue ?? null);

    $labelClass = $hasError ? $colorPreset['label_error'] ?? $colorPreset['label'] : $colorPreset['label'];

    $positions = [
        'top' => 'flex flex-col gap-1',
        'bottom' => 'flex flex-col gap-1',
        'left' => 'inline-flex items-center gap-2',
        'right' => 'inline-flex items-center gap-2',
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
        $hasError ? $colorPreset['border_error'] ?? '' : '',
        $hasError ? $colorPreset['focus_error'] ?? ($colorPreset['focus'] ?? '') : $colorPreset['focus'] ?? '',
        $colorPreset['active'] ?? '',
    ]);

    $inputId = $attributes->get('id') ?? 'beartropy-toggle-' . uniqid();
@endphp

<div class="transition-colors duration-300 rounded-lg p-2"
    :class="{
        'border border-dotted border-gray-400 dark:border-gray-600': saving,
        'border-2 border-emerald-500': saved && !saving && !error,
        'border-2 border-red-500': error && !saving,
        'border border-transparent': !saving && !saved && !error
    }"
    x-data="{
        autosave: {{ $autosave ? 'true' : 'false' }},
        method: @js($autosaveMethod),
        key: @js($autosaveKey),
        debounceMs: {{ (int) $autosaveDebounce }},
        saving: false,
        saved: false,
        error: false,
        _t: null,
    
        @if ($hasWireModel) checked: $wire.entangle(@js($wireModelValue)).live,
        @else
            checked: {{ $attributes->has('checked') ? 'true' : 'false' }}, @endif
    
        triggerAutosave() {
            if (!this.autosave) return;
    
            this.saved = false;
            this.error = false;
            this.saving = true;
    
            clearTimeout(this._t);
            const val = !!this.checked;
    
            this._t = setTimeout(async () => {
                try {
                    await $wire.call(this.method, val, this.key);
                    this.saving = false;
                    this.saved = true;
                } catch (e) {
                    console.error(e);
                    this.saving = false;
                    this.error = true;
                }
            }, this.debounceMs);
        }
    }">
    {{-- Toggle content --}}
    <div class="flex items-center justify-center gap-3">
        <div class="{{ $layoutClasses }} min-h-full justify-center">
            @if ($labelPosition === 'top' && (trim($slot) || $label))
                <label for="{{ $inputId }}"
                    class="{{ $sizePreset['font'] }} {{ $labelClass }} cursor-pointer select-none">
                    @if (trim($slot))
                        {{ $slot }}
                    @elseif ($label)
                        {{ $label }}
                    @endif
                </label>
            @endif

            <label
                class="inline-flex items-center cursor-pointer select-none gap-2 relative {{ $disabled ? $colorPreset['disabled'] : '' }}">
                @if ($labelPosition === 'left' && (trim($slot) || $label))
                    <span class="{{ $sizePreset['font'] }} {{ $labelClass }}">
                        @if (trim($slot))
                            {{ $slot }}
                        @elseif ($label)
                            {{ $label }}
                        @endif
                    </span>
                @endif

                <div class="relative">
                    <input class="peer sr-only" id="{{ $inputId }}" type="checkbox"
                        {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['id' => $inputId]) }}
                        x-model="checked" @change="triggerAutosave()">
                    <span class="{{ $trackClass }}"></span>
                    <span
                        class="absolute transition rounded-full
                        {{ $sizePreset['thumb'] }}
                        {{ $sizePreset['thumbTop'] ?? 'top-1' }}
                        {{ $sizePreset['thumbLeft'] ?? 'left-1' }}
                        {{ $colorPreset['thumb'] ?? '' }}
                        {{ $sizePreset['thumbTranslate'] }}"></span>
                </div>

                @if ($labelPosition === 'right' && (trim($slot) || $label))
                    <span class="{{ $sizePreset['font'] }} {{ $labelClass }}">
                        @if (trim($slot))
                            {{ $slot }}
                        @elseif ($label)
                            {{ $label }}
                        @endif
                    </span>
                @endif
            </label>

            @if ($labelPosition === 'bottom' && (trim($slot) || $label))
                <label for="{{ $inputId }}"
                    class="{{ $sizePreset['font'] }} {{ $labelClass }} cursor-pointer select-none">
                    @if (trim($slot))
                        {{ $slot }}
                    @elseif ($label)
                        {{ $label }}
                    @endif
                </label>
            @endif
        </div>

        {{-- Autosave indicators --}}
        @if ($autosave)
            <div class="min-w-6 flex items-center justify-end">
                <svg x-show="saving" class="w-4 h-4 animate-spin text-gray-400" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity=".25" />
                    <path d="M21 12a9 9 0 0 1-9 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                <!-- ok -->
                <svg x-show="saved" class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <!-- error -->
                <svg x-show="error" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        @endif
    </div>

    <x-beartropy-ui::support.field-help :error-message="$finalError" :hint="$help ?? $hint" />
</div>
