@php
    [$colorPreset, $sizePreset, $shouldFill] = $getComponentPresets('input');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();
    $inputId = $attributes->get('id') ?? 'taginput-' . uniqid();
    $borderClass = $hasError ? ($colorPreset['border_error'] ?? $colorPreset['border']) : $colorPreset['border'];
    $ringClass = $hasError ? ($colorPreset['ring_error'] ?? $colorPreset['ring']) : $colorPreset['ring'];
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];
@endphp

<div
    x-data="$beartropy.tagInput({
        @if($hasWireModel)
            initialTags: @entangle($attributes->wire('model')),
        @else
            initialTags: @js($value ?? []),
        @endif
        unique: {{ $unique ? 'true' : 'false' }},
        maxTags: {{ $maxTags ?? 'null' }},
        disabled: {{ $disabled ? 'true' : 'false' }},
        separator: @js($separator),
    })"
    class="flex flex-col w-full"
>
    @if($label)
        <label for="{{ $inputId }}" class="{{ $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'] }}">
            {!! $label !!}
        </label>
    @endif

    <div
        class="flex items-center group w-full rounded-lg transition-all shadow-sm outline-none overflow-hidden
            {{ $shouldFill ? $colorPreset['bg'] : 'bg-white dark:bg-gray-900' }}
            {{ $borderClass ?? '' }}
            {{ $ringClass ?? '' }}
            {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}
            beartropy-taginput-chips"
        @if($disabled) aria-disabled="true" @endif
        @click="focusInput"
    >
        {{-- Start slot --}}
        @if (trim($start ?? ''))
            <div class="flex shrink-0 self-stretch beartropy-inputbase-start-slot">
                {{ $start }}
            </div>
        @endif

        {{-- Chips + input --}}
        <div class="flex flex-wrap gap-1 items-center w-full pl-3 {{ $sizePreset['minHeight'] }} max-h-32 overflow-y-auto beartropy-thin-scrollbar" wire:ignore>
            <template x-for="(tag, i) in tags" :key="tag">
                <span class="flex items-center gap-1 px-2 py-1 rounded {{ $colorPreset['chip_bg'] }} {{ $colorPreset['chip_text'] }} text-sm">
                    <span x-text="tag"></span>
                    <button
                        type="button"
                        x-show="!disabled"
                        class="ml-1 {{ $colorPreset['chip_close'] }}"
                        @click.stop="removeTag(i)"
                        tabindex="-1"
                    >
                        &times;
                    </button>
                </span>
            </template>
            <input
                x-ref="input"
                x-model="input"
                @keydown.enter.prevent="addTag()"
                @keydown.tab="addTagOnTab($event)"
                @keydown.backspace="removeOnBackspace"
                @blur="addTag()"
                :disabled="disabled"
                id="{{ $inputId }}"
                class="flex-1 bg-transparent outline-none border-none shadow-none min-w-[120px] beartropy-input
                    {{ $sizePreset['font'] ?? '' }}
                    {{ $colorPreset['text'] ?? '' }}
                    {{ $colorPreset['placeholder'] ?? '' }}
"
                :placeholder="tags.length === 0 ? '{{ $placeholder }}' : ''"
                autocomplete="off"
                @paste="handlePaste"
                style="min-width: 80px;"
            >

        </div>

        {{-- End slot --}}
        @if (trim($end ?? ''))
            <div class="flex shrink-0 self-stretch beartropy-inputbase-end-slot">
                {{ $end }}
            </div>
        @endif
    </div>

    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
