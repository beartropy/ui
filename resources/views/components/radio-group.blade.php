@php
    [$colorPreset, $sizePreset] = $getComponentPresets('radio');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError);

    $wrapperClass = $inline
        ? 'flex gap-4 flex-wrap'
        : 'flex flex-col gap-2';
@endphp

<div>
    @if($label)
        <span class="block text-sm font-medium mb-1 {{ $hasError ? ($colorPreset['label_error'] ?? 'text-red-500') : ($colorPreset['label'] ?? 'text-gray-800 dark:text-gray-100') }}">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </span>
    @endif

    <div class="{{ $wrapperClass }}" {{ $attributes->only(['class', 'style']) }}>
        @foreach ($options as $option)
            <x-beartropy-ui::radio
                name="{{ $name }}"
                value="{{ $option['value'] }}"
                label="{{ $option['label'] ?? '' }}"
                color="{{ $color ?? 'beartropy' }}"
                size="{{ $size ?? 'md' }}"
                :disabled="$disabled"
                :grouped="true"
                :checked="(string) $value === (string) $option['value']"
                {{ $attributes->whereStartsWith('wire:model') }}
            />
        @endforeach
    </div>

    <x-beartropy-ui::support.field-help :error-message="$finalError" :hint="$help ?? $hint" />
</div>
