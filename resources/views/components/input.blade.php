@php
    [$colorPreset, $sizePreset] = $getComponentPresets('input');

    $inputId = $attributes->get('id') ?? 'input-' . uniqid();
    $wireModelName = $attributes->wire('model')->value();

    // Detección de modo Alpine/Livewire
    $alpineControlled = $attributes->has('x-model');
    $xModel = $alpineControlled ? $attributes->get('x-model') : null;
    $isLivewire = !!$wireModelName;
    $isAlpineExternal = !!$xModel;
    $isAlpineLocal = !$isLivewire && !$isAlpineExternal;


    $extraInputAttrs = [];
    if ($isLivewire) {
        $inputId = $attributes->wire('model')->value();
    } elseif ($isAlpineExternal) {
        $extraInputAttrs['x-model'] = $xModel;
    } elseif ($isAlpineLocal) {
        $extraInputAttrs['x-model'] = 'value';
        $extraInputAttrs['value'] = $value ?? '';
    }

    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];

    $loadingTargetsOverride = null;
    if($attributes->get('wire:target')) {
        $wireLoadingTargetsCsv = $attributes->get('wire:target');
    } else {
        $wireActionTargets = collect($attributes->getAttributes())
            ->filter(fn ($v, $k) => Str::startsWith($k, 'wire:'))
            ->reject(fn ($v, $k) => $k === 'wire:model')
            ->reject(fn ($v, $k) => $k === 'wire:model.live')
            ->reject(fn ($v, $k) => $k === 'wire:model.debounce')

            ->map(function ($v) {
                if (is_string($v)) return $v;
                if (is_array($v)) return head($v);
                return null;
            })
            ->filter()
            ->unique()
            ->values();

        $wireLoadingTargets = $loadingTargetsOverride
            ? collect(is_array($loadingTargetsOverride) ? $loadingTargetsOverride : explode(',', (string) $loadingTargetsOverride))
                ->map(fn($s) => trim($s))
                ->filter()
                ->unique()
                ->values()
            : $wireActionTargets;

        $wireLoadingTargetsCsv = $wireLoadingTargets->implode(',');
    }

    $wrapperClass = $attributes->get('class') ?? '';

@endphp

<div class="flex flex-col w-full {{ $wrapperClass }}">
    @if($label)
        <label for="{{ $inputId }}" class="{{ $labelClass }}">
            {{ $label }}
        </label>
    @endif

    <x-beartropy-ui::base.input-base
        id="{{ $inputId }}"
        type="{{ $type }}"
        size="{{ $size }}"
        color="{{ $color }}"
        placeholder="{{ $placeholder }}"
        custom-error="{{ $customError }}"
        hint="{{ $hint }}"
        has-error="{{ $hasError }}"
        {{ $attributes->merge($extraInputAttrs) }}
    >
        {{-- START SLOT --}}
        @if(isset($iconStart) || isset($start))
            <x-slot name="start">
                @if($iconStart)
                    <span class="flex items-center {{ $colorPreset['text'] ?? '' }}">
                        {{-- Icono --}}
                        <x-beartropy-ui::icon :name="$iconStart" size="{{ $size }}" />
                    </span>
                @endif
                {{-- Slot personalizado --}}
                @isset($start)
                    {{ $start }}
                @endisset
            </x-slot>
        @endif

        @if(isset($end) || $clearable || $copyButton || ($type === 'password' && $togglePassword) || $iconEnd || (!empty($wireLoadingTargetsCsv) && $spinner))
            <x-slot name="end">
            @if(!empty($wireLoadingTargetsCsv) && $spinner)
                <span
                    wire:loading
                    wire:target="{{ $wireLoadingTargetsCsv }}"
                    aria-label="Cargando…"
                    class="inline-flex items-center"
                >
                    @include('beartropy-ui-svg::beartropy-spinner', [
                        'class' => 'animate-spin shrink-0 text-gray-700 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 ' . ($sizePreset['iconSize'] ?? '')
                    ])
                </span>
            @endif
            {{-- Botón limpiar --}}
            @if($clearable)
                <button
                    type="button"
                    x-show="value.length > 0"
                    x-on:click="clear"
                    tabindex="-1"
                    aria-label="Clear"
                >
                    @include('beartropy-ui-svg::beartropy-x-mark', [
                        'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 ' . ($sizePreset['iconSize'] ?? '')
                    ])
                </button>
            @endif

            {{-- Botón copiar --}}
            @if($copyButton)
                <button
                    type="button"
                    x-on:click="copyToClipboard"
                    x-tooltip.raw="Copiar"
                    tabindex="-1"
                    aria-label="Copiar al portapapeles"
                >
                    <span x-show="!copySuccess">
                        @include('beartropy-ui-svg::beartropy-clipboard', [
                            'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 ' . ($sizePreset['iconSize'] ?? '')
                        ])
                    </span>
                    <span x-show="copySuccess" class="text-green-500">
                        @include('beartropy-ui-svg::beartropy-check', [
                            'class' => 'shrink-0 ' . ($sizePreset['iconSize'] ?? '')
                        ])
                    </span>
                </button>
            @endif

            {{-- Toggle password --}}
            @if($type === 'password')
                <button
                    type="button"
                    x-on:click="showPassword = !showPassword"
                    tabindex="-1"
                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                >
                    <span x-show="!showPassword">
                        @include('beartropy-ui-svg::beartropy-eye', [
                            'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 ' . ($sizePreset['iconSize'] ?? '')
                        ])
                    </span>
                    <span x-show="showPassword">
                        @include('beartropy-ui-svg::beartropy-eye-slash', [
                            'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 ' . ($sizePreset['iconSize'] ?? '')
                        ])
                    </span>
                </button>
            @endif

            @if($iconEnd)
                <span class="{{ $colorPreset['text'] ?? '' }}">
                    <x-beartropy-ui::icon :name="$iconEnd" size="{{ $size }}" />
                </span>
            @endif

            {{-- Slot personalizado --}}
            @isset($end)
                {{ $end }}
            @endisset
        </x-slot>
        @endif
    </x-beartropy-ui::base.input-base>

    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
