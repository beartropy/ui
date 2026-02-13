@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('input');
    [$colorDropdown] = $getComponentPresets('select');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);

    $inputId = $id;
    $wireModelName = $attributes->wire('model')->value();
    $isLivewire = !!$wireModelName;

    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];
    $wrapperClass = $attributes->get('class') ?? '';

    // Auto-detect wire:target for loading spinner
    $wireLoadingTargetsCsv = $attributes->get('wire:target')
        ?: collect($attributes->getAttributes())
            ->filter(fn ($v, $k) => Str::startsWith($k, 'wire:'))
            ->reject(fn ($v, $k) => in_array($k, ['wire:model', 'wire:model.live', 'wire:model.debounce']))
            ->map(fn ($v) => is_string($v) ? $v : (is_array($v) ? head($v) : null))
            ->filter()
            ->unique()
            ->values()
            ->implode(',');

    // Build extra attributes for input-base (constructor props aren't in $attributes)
    $extraInputAttrs = [];
    if ($disabled) {
        $extraInputAttrs['disabled'] = true;
    }
    if ($readonly) {
        $extraInputAttrs['readonly'] = true;
    }
@endphp

<div
    class="flex flex-col w-full relative {{ $wrapperClass }}"
    data-options='@json($options)'
    x-data="beartropyLookup({
        inputId: @js($inputId),
        isLivewire: {{ $isLivewire ? 'true' : 'false' }},
        labelKey: @js($optionLabel),
        valueKey: @js($optionValue),
        wireModelName: @js($wireModelName),
    })"
>
    @if($label)
        <label for="{{ $inputId }}" class="{{ $labelClass }}">
            {{ $label }}
        </label>
    @endif

    @if($isLivewire && $wireModelName)
        <input type="hidden"
            x-ref="livewireValue"
            {{ $attributes->whereStartsWith('wire:model')->merge() }}>
    @endif

    <x-beartropy-ui::base.input-base
        id="{{ $inputId }}"
        type="text"
        size="{{ $size }}"
        color="{{ $color }}"
        placeholder="{{ $placeholder }}"
        custom-error="{{ $customError }}"
        hint="{{ $hint }}"
        has-error="{{ $hasError }}"
        value="{{ $value ?? '' }}"
        @click="open = true"
        x-on:input="onInput($event)"
        x-on:keydown.down.prevent="move(1)"
        x-on:keydown.up.prevent="move(-1)"
        x-on:keydown.enter.prevent="confirm()"
        x-on:keydown.tab="confirm()"
        x-on:keydown.escape.prevent="close()"
        x-on:blur="confirm()"
        {{ $attributes->whereDoesntStartWith('wire:model')->except(['class'])->merge($extraInputAttrs) }}
    >
        {{-- Start slot --}}
        @if(isset($iconStart) || isset($start))
            <x-slot name="start">
                @if($iconStart)
                    <span class="flex items-center px-2 {{ $colorPreset['text'] ?? '' }}">
                        <x-beartropy-ui::icon :name="$iconStart" size="{{ $size }}" />
                    </span>
                @endif
                @isset($start)
                    {{ $start }}
                @endisset
            </x-slot>
        @endif

        @if(isset($end) || $clearable || $iconEnd || !empty($wireLoadingTargetsCsv))
            <x-slot name="end">
                @if(!empty($wireLoadingTargetsCsv) || $clearable || $iconEnd)
                    <div class="flex items-center gap-1 px-2">
                        @if(!empty($wireLoadingTargetsCsv))
                            <span
                                wire:loading
                                wire:target="{{ $wireLoadingTargetsCsv }}"
                                aria-label="{{ __('beartropy-ui::ui.loading') }}"
                                class="inline-flex items-center"
                            >
                                @include('beartropy-ui-svg::beartropy-spinner', [
                                    'class' => 'animate-spin shrink-0 text-gray-700 dark:text-gray-400 ' . ($sizePreset['iconSize'] ?? '')
                                ])
                            </span>
                        @endif

                        @if($clearable)
                            <button
                                type="button"
                                x-show="value.length > 0"
                                x-on:click="clearBoth()"
                                tabindex="-1"
                                aria-label="{{ __('beartropy-ui::ui.clear') }}"
                            >
                                @include('beartropy-ui-svg::beartropy-x-mark', [
                                    'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 ' . ($sizePreset['iconSize'] ?? '')
                                ])
                            </button>
                        @endif

                        @if($iconEnd)
                            <span class="{{ $colorPreset['text'] ?? '' }}">
                                <x-beartropy-ui::icon :name="$iconEnd" size="{{ $size }}" />
                            </span>
                        @endif
                    </div>
                @endif

                @isset($end)
                    {{ $end }}
                @endisset
            </x-slot>
        @endif

        <x-slot name="dropdown">
            <x-beartropy-ui::base.dropdown-base
                placement="left"
                side="bottom"
                color="{{ $presetNames['color'] ?? '' }}"
                preset-for="select"
                width="w-full"
                x-show="open"
                @click.outside="close()"
            >
                <template x-if="filtered.length">
                    <ul class="max-h-60 overflow-auto beartropy-thin-scrollbar" role="listbox">
                        <template x-for="(opt, idx) in filtered" :key="idx">
                            <li
                                role="option"
                                class="px-3 py-2 cursor-pointer select-none text-sm {{ $colorDropdown['option_text'] ?? 'text-gray-700 dark:text-gray-300' }} {{ $colorDropdown['option_hover'] ?? '' }}"
                                :class="idx === highlighted ? '{{ $colorDropdown['option_active'] ?? 'bg-neutral-100 dark:bg-neutral-800' }}' : ''"
                                @mouseenter="highlighted = idx"
                                @mousedown.prevent="choose(idx)"
                            >
                                <span x-text="getLabel(opt)"></span>
                            </li>
                        </template>
                    </ul>
                </template>
            </x-beartropy-ui::base.dropdown-base>
        </x-slot>
    </x-beartropy-ui::base.input-base>

    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
