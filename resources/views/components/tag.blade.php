@php
    [$colorPreset, $sizePreset, $shouldFill] = $getComponentPresets('input');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $inputId = $id;
    $inputName = $wireModelValue ?: $name;
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];
    $borderClass = $hasError ? ($colorPreset['border_error'] ?? $colorPreset['border']) : $colorPreset['border'];
    $ringClass = $hasError ? ($colorPreset['ring_error'] ?? $colorPreset['ring']) : $colorPreset['ring'];
    $wrapperClass = $attributes->get('class') ?? '';

    $chipClasses = match($sizePreset['font'] ?? 'text-base') {
        'text-xs' => 'text-xs px-1.5 py-0.5',
        'text-sm' => 'text-xs px-2 py-0.5',
        'text-lg' => 'text-sm px-2.5 py-1',
        'text-xl' => 'text-base px-3 py-1.5',
        default => 'text-sm px-2 py-1',
    };
@endphp

<div
    x-data="beartropyTagInput({
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
    class="flex flex-col w-full {{ $wrapperClass }}"
    {{ $attributes->except(['class', 'id', 'wire:model', 'wire:model.live', 'wire:model.blur', 'wire:model.lazy']) }}
>
    @if($label)
        <label for="{{ $inputId }}" class="{{ $labelClass }}">
            {{ $label }}
        </label>
    @endif

    <div
        class="flex items-stretch group w-full rounded-lg transition-all shadow-sm outline-none overflow-hidden
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
        <div class="flex flex-wrap gap-1 items-center w-full pl-3 py-1 {{ $sizePreset['minHeight'] }} max-h-32 overflow-y-auto beartropy-thin-scrollbar" wire:ignore>
            <template x-for="(tag, i) in tags" :key="'chip-'+tag+i">
                <span class="flex items-center gap-1 {{ $chipClasses }} rounded {{ $colorPreset['chip_bg'] }} {{ $colorPreset['chip_text'] }}">
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
                    {{ $colorPreset['placeholder'] ?? '' }}"
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

    {{-- Hidden inputs for form submission --}}
    @unless($hasWireModel)
        <template x-for="(tag, i) in tags" :key="'hidden-'+tag+i">
            <input type="hidden" :name="`{{ $inputName }}[]`" :value="tag">
        </template>
    @endunless

    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$hint ?? $help ?? null"
    />
</div>
