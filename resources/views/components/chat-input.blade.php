@php
    [$colorPreset, $sizePreset] = $getComponentPresets('chat-input');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $id = $id ?? ($name ?? uniqid('chat-input-'));
    $name = $name ?? $id;

    $wrapperClass = $hasError ? $colorPreset['wrapper_error'] ?? $colorPreset['wrapper'] : $colorPreset['wrapper'];
@endphp

<div class="mb-4">
    @if ($label)
        <label for="{{ $id }}" class="{{ $hasError ? $colorPreset['label_error'] : $colorPreset['label'] }}">
            {{ $label }} @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $wrapperClass }} {{ $colorPreset['main'] ?? '' }}" x-data="{
        val: '',
        resize() {
            $refs.textarea.style.height = 'auto';
            $refs.textarea.style.height = $refs.textarea.scrollHeight + 'px';
        }
    }">
        <textarea id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}" rows="{{ $rows }}"
            @if ($disabled) disabled @endif @if ($readonly) readonly @endif
            @if ($required) required @endif
            @if ($maxLength) maxlength="{{ $maxLength }}" @endif x-ref="textarea" x-model="val"
            class="{{ $colorPreset['input'] }}" x-init="resize()" x-on:input="resize()"
            {{ $attributes->whereDoesntStartWith('wire:model') }}
            @if ($hasWireModel) wire:model="{{ $wireModelValue }}" @endif>{{ old($name, $slot) }}</textarea>

        @if (isset($footer))
            <div class="{{ $colorPreset['footer'] }}">
                {{ $footer }}
            </div>
        @endif
    </div>

    <x-beartropy-ui::support.field-help :error-message="$finalError" :hint="$help ?? ($hint ?? null)" />
</div>
