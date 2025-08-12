@props([
    'name',
    'options' => [], // [ ['value' => '...', 'label' => '...'] ]
    'color' => 'beartropy',
    'size' => 'md',
    'inline' => false,
    'disabled' => false,
    'required' => false,
    'class' => '',
    'error' => null,
])

@php
    [$colorPreset, $sizePreset] = $getComponentPresets('radio');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    $wrapperClass = $inline
        ? 'flex gap-4 flex-wrap'
        : 'flex flex-col gap-2';
@endphp

<div>
    <div class="{{ $wrapperClass }} {{ $class }}">
    @foreach ($options as $option)
        <x-beartropy-ui::radio
            name="{{ $name }}"
            value="{{ $option['value'] }}"
            label="{{ $option['label'] ?? '' }}"
            color="{{ $color }}"
            size="{{ $size }}"
            :grouped="true"
            group-error="{{ $hasError }}"
            {{ $attributes->whereStartsWith('wire:model') }}
        />
    @endforeach

    </div>
    <x-beartropy-ui::support.field-help :error-message="$finalError" :hint="$hint ?? null" />
</div>
