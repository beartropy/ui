@php
    [$colorPreset, $sizePreset] = $getComponentPresets('chat-input');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $inputId = $id;
    $inputName = $wireModelValue ?: $name;
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];

    $wrapperClass = $hasError ? ($colorPreset['wrapper_error'] ?? $colorPreset['wrapper']) : $colorPreset['wrapper'];
    if ($border) {
        $borderClass = $colorPreset['border'] ?? '';
        $wrapperClass .= ' ' . $borderClass;
    }

    $extraClass = $attributes->get('class') ?? '';
@endphp

<div class="{{ isset($footer) || isset($actions) ? 'mb-4' : '' }}">
    @if ($label)
        <label for="{{ $inputId }}" class="{{ $labelClass }}">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div
        x-cloak
        x-data="beartropyChatInput({
            value: @if ($hasWireModel) @entangle($attributes->wire('model')) @else @js($slot->toHtml()) @endif,
            isSingleLine: {{ $stacked ? 'false' : 'true' }},
            stacked: {{ $stacked ? 'true' : 'false' }},
            action: @js($action),
            submitOnEnter: {{ $submitOnEnter ? 'true' : 'false' }},
            disabled: {{ $disabled ? 'true' : 'false' }},
        })"
        class="{{ $wrapperClass }} {{ $extraClass }}"
        :class="isSingleLine && !stacked
            ? 'grid grid-cols-[auto_1fr_auto] items-center gap-x-2'
            : 'grid grid-cols-2 gap-y-2 items-center'"
        {{ $attributes->except(['class', 'id', 'wire:model', 'wire:model.live', 'wire:model.blur', 'wire:model.lazy', 'wire:click', 'wire:keydown']) }}
    >
        @if (isset($tools))
            <div class="text-gray-400"
                :class="isSingleLine && !stacked
                    ? 'col-start-1 pl-2'
                    : 'col-start-1 row-start-2 justify-self-start pl-2 pb-2'">
                {{ $tools }}
            </div>
        @endif

        <textarea
            id="{{ $inputId }}"
            name="{{ $inputName }}"
            placeholder="{{ $placeholder }}"
            rows="1"
            @if ($disabled) disabled @endif
            @if ($readonly) readonly @endif
            @if ($required) required @endif
            @if ($maxLength) maxlength="{{ $maxLength }}" @endif
            x-ref="textarea"
            x-model="val"
            x-on:input="resize()"
            x-on:keydown.enter="handleEnter($event)"
            class="{{ $colorPreset['input'] }} py-2 min-w-0 max-h-60 overflow-y-hidden beartropy-textarea beartropy-thin-scrollbar"
            style="field-sizing: content;"
            :class="isSingleLine && !stacked ? 'col-start-2' : 'col-span-2'"
            @if ($hasWireModel) wire:model="{{ $wireModelValue }}" @endif
        ></textarea>

        @if (isset($footer) || isset($actions))
            <div {{ ($actions ?? $footer)->attributes->merge(['class' => $colorPreset['footer']]) }}
                :class="isSingleLine && !stacked
                    ? 'col-start-3 !p-0 !pr-2 py-2'
                    : 'col-start-2 row-start-2 justify-self-end !p-0 !pr-2 !pb-2'">
                {{ $actions ?? $footer }}
            </div>
        @endif
    </div>

    <x-beartropy-ui::support.field-help :error-message="$finalError" :hint="$help ?? ($hint ?? null)" />
</div>
