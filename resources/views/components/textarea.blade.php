@php
    [$colorPreset, $sizePreset] = $getComponentPresets('textarea');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();
    $borderClass = $hasError ? ($colorPreset['border_error'] ?? $colorPreset['border_default']) : $colorPreset['border_default'];
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];

    $id = $id ?? $name ?? uniqid('textarea-');

    $resizeClass = $resize

        ? 'resize-' . $resize
        : ($autoResize ? 'resize-none' : 'resize-y');

    $value = old($name, $slot);
    $countBlade = is_string($value) ? mb_strlen($value) : 0;
@endphp

<div class="mb-4">
    @if($label)
        <label
            for="{{ $id }}"
            class="{{ $colorPreset['label'] }} {{ $hasError ? $colorPreset['label_error'] : '' }}"
        >
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <div
        class="{{ $colorPreset['main'] }} {{ $hasError ? $colorPreset['border_error'] : $colorPreset['border_default'] }} min-h-[2.5rem] relative"
        x-data="{
            count: $refs.textarea?.value?.length || 0,
            copySuccess: false,
            update() { this.count = $refs.textarea?.value?.length || 0; },
            copy() {
                navigator.clipboard.writeText($refs.textarea.value);
                this.copySuccess = true;
                setTimeout(() => this.copySuccess = false, 1600);
            }
        }"
        x-init="update(); setInterval(() => update(), 100);"
    >
        {{-- Copy button (arriba a la derecha) --}}
        @if($showCopyButton)
            <button
                type="button"
                class="absolute top-2 right-3 z-10 p-1 rounded transition"
                x-on:click="copy()"
                x-tooltip.raw="Copiar"
                :disabled="copySuccess"
                tabindex="-1"
                aria-label="Copiar contenido"
            >
                <span x-show="!copySuccess">
                    @include('beartropy-ui-svg::beartropy-clipboard', ['class' => 'w-5 h-5 shrink-0 text-gray-500 dark:text-gray-400'])
                </span>
                <span x-show="copySuccess">
                    @include('beartropy-ui-svg::beartropy-check', ['class' => 'w-5 h-5 shrink-0 text-green-500 bg-transparent'])
                </span>
            </button>
        @endif

        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            rows="{{ $rows }}"
            @if($cols) cols="{{ $cols }}" @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($required) required @endif
            @if($maxLength) maxlength="{{ $maxLength }}" @endif
            x-ref="textarea"
            {{ $attributes->merge([
                // Solo pr-2 acá, para evitar que el scroll se meta en el padding
                'class' => $colorPreset['input'] . '  beartropy-thin-scrollbar pr-3 ' . $resizeClass
            ]) }}
            @if($autoResize)
                x-init="$el.style.height = $el.scrollHeight + 'px'"
                x-on:input="
                    $el.style.height = 'auto';
                    $el.style.height = $el.scrollHeight + 'px';
                    count = $el.value.length"
            @elseif($showCounter || $showCopyButton)
                x-on:input="count = $el.value.length"
            @endif
        >{{ old($name, $slot) }}</textarea>

        {{-- Contador (abajo a la derecha) --}}
        @if($showCounter)
            <span class="absolute bottom-3.5 right-3 text-xs select-none"
                :class="(typeof count !== 'undefined' && {{ $maxLength ?? 'null' }} && count >= {{ $maxLength ?? 'null' }}) ? 'text-red-500 dark:text-red-400 font-semibold' : 'text-gray-400 dark:text-gray-500'"
                x-text="count + ({{ $maxLength ? '\'' . ' / ' . $maxLength . '\'' : "''" }})"
            >
                {{ $countBlade }}{{ $maxLength ? ' / '.$maxLength : '' }}
            </span>
        @endif
    </div>

    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
