@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('input');
    [$colorTimepicker] = $getComponentPresets('time-picker');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $pickerId = $id;
    $inputName = $wireModelValue ?: $name;
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];
    $placeholderText = $placeholder ?? __('beartropy-ui::ui.select_time');

    $is12h = (bool) preg_match('/[hgGA]/', $format);

    $wrapperClass = $attributes->get('class') ?? '';
@endphp

<div
    x-data="beartropyTimepicker({
        value: @if($hasWireModel) @entangle($attributes->wire('model')) @else @js($value) @endif,
        is12h: {{ $is12h ? 'true' : 'false' }},
        showSeconds: {{ $seconds ? 'true' : 'false' }},
        min: @js($min),
        max: @js($max),
        interval: {{ $interval }},
        disabled: {{ $disabled ? 'true' : 'false' }},
        i18n: { now: '{{ __('beartropy-ui::ui.now') }}' },
    })"
    @click.outside="open = false"
    class="flex flex-col w-full {{ $wrapperClass }}"
    {{ $attributes->except(['class', 'id', 'wire:model', 'wire:model.live', 'wire:model.blur', 'wire:model.lazy', 'fill', 'outline']) }}
>
    @if($label)
        <label for="{{ $pickerId }}" class="{{ $labelClass }}">
            {{ $label }}
        </label>
    @endif

    <x-beartropy-ui::base.input-trigger-base
        id="{{ $pickerId }}"
        color="{{ $presetNames['color'] }}"
        size="{{ $presetNames['size'] }}"
        custom-error="{{ $customError }}"
        hint="{{ $hint }}"
        has-error="{{ $hasError }}"
        :disabled="$disabled"
        {{ $attributes->only(['fill', 'outline']) }}
    >
        <x-slot name="button">
            <div @click="if (!disabled) open = !open" class="flex items-center gap-2 min-h-[1.6em] w-full truncate" :class="disabled ? 'cursor-not-allowed' : 'cursor-pointer'">
                <span x-text="displayLabel || '{{ $placeholderText }}'"
                    :class="!displayLabel ? 'beartropy-placeholder text-sm text-gray-400 dark:text-gray-500' : '{{ $colorTimepicker['option_text'] ?? 'text-gray-800 dark:text-gray-100' }}'"></span>
            </div>
        </x-slot>

        <x-slot name="end">
            <div class="flex items-center gap-0.5 px-2">
                @if($clearable)
                    <button
                        type="button"
                        x-show="value"
                        @click.stop="clear()"
                        class="grid place-items-center w-5 h-5 rounded transition hover:bg-gray-100 dark:hover:bg-white/5"
                        title="{{ __('beartropy-ui::ui.clear') }}"
                        x-cloak
                    >
                        @include('beartropy-ui-svg::beartropy-x-mark', [
                            'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 ' . ($sizePreset['iconSize'] ?? '')
                        ])
                    </button>
                @endif
                <button type="button" @click="if (!disabled) open = !open" :class="disabled ? 'cursor-not-allowed' : 'cursor-pointer'" class="grid place-items-center w-5 h-5">
                    @include('beartropy-ui-svg::beartropy-clock', [
                         'class' => 'w-5 h-5 transition-transform duration-200 text-gray-400 dark:text-gray-500'
                    ])
                </button>
            </div>
        </x-slot>

        <x-slot name="dropdown">
            <x-beartropy-ui::base.dropdown-base
                placement="bottom-start"
                side="bottom"
                color="{{ $presetNames['color'] }}"
                preset-for="time-picker"
                width="w-auto"
                :fit-anchor="false"
                x-show="open"
                triggerLabel="{{ $label }}"
                x-transition
            >
                <div class="select-none">
                    <div class="flex items-start justify-center px-5 pt-4 pb-3">
                        {{-- Hour wheel --}}
                        <div class="flex flex-col items-center"
                            @wheel.prevent="wheelHour($event)"
                            @keydown.up.prevent="moveHour(-1)"
                            @keydown.down.prevent="moveHour(1)"
                            tabindex="0"
                            role="listbox"
                            aria-label="{{ __('beartropy-ui::ui.hour') }}"
                        >
                            <span class="{{ $colorTimepicker['column_label'] ?? 'text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500' }} mb-2">{{ __('beartropy-ui::ui.hour') }}</span>
                            <button type="button" @click="moveHour(-1)"
                                class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorTimepicker['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                :class="hour === null && 'invisible'" x-text="getAdjacentHour(-1)" tabindex="-1"
                            ></button>
                            <div class="h-12 w-14 flex items-center justify-center rounded-xl {{ $colorTimepicker['wheel_highlight'] ?? 'bg-beartropy-50 dark:bg-beartropy-950/30' }}">
                                <span class="text-[1.75rem] font-bold tabular-nums leading-none {{ $colorTimepicker['wheel_selected'] ?? 'text-beartropy-600 dark:text-beartropy-400' }}" x-text="hour ?? '--'"></span>
                            </div>
                            <button type="button" @click="moveHour(1)"
                                class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorTimepicker['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                :class="hour === null && 'invisible'" x-text="getAdjacentHour(1)" tabindex="-1"
                            ></button>
                        </div>

                        {{-- Separator --}}
                        <div class="flex flex-col items-center px-0.5">
                            <span class="{{ $colorTimepicker['column_label'] ?? 'text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500' }} mb-2 invisible">&nbsp;</span>
                            <div class="h-9"></div>
                            <div class="h-12 flex items-center justify-center">
                                <span class="text-2xl font-bold text-gray-300 dark:text-gray-600 select-none">:</span>
                            </div>
                        </div>

                        {{-- Minute wheel --}}
                        <div class="flex flex-col items-center"
                            @wheel.prevent="wheelMinute($event)"
                            @keydown.up.prevent="moveMinute(-1)"
                            @keydown.down.prevent="moveMinute(1)"
                            tabindex="0"
                            role="listbox"
                            aria-label="{{ __('beartropy-ui::ui.minute_short') }}"
                        >
                            <span class="{{ $colorTimepicker['column_label'] ?? 'text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500' }} mb-2">{{ __('beartropy-ui::ui.minute_short') }}</span>
                            <button type="button" @click="moveMinute(-1)"
                                class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorTimepicker['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                :class="minute === null && 'invisible'" x-text="getAdjacentMinute(-1)" tabindex="-1"
                            ></button>
                            <div class="h-12 w-14 flex items-center justify-center rounded-xl {{ $colorTimepicker['wheel_highlight'] ?? 'bg-beartropy-50 dark:bg-beartropy-950/30' }}">
                                <span class="text-[1.75rem] font-bold tabular-nums leading-none {{ $colorTimepicker['wheel_selected'] ?? 'text-beartropy-600 dark:text-beartropy-400' }}" x-text="minute ?? '--'"></span>
                            </div>
                            <button type="button" @click="moveMinute(1)"
                                class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorTimepicker['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                :class="minute === null && 'invisible'" x-text="getAdjacentMinute(1)" tabindex="-1"
                            ></button>
                        </div>

                        @if($seconds)
                            {{-- Separator --}}
                            <div class="flex flex-col items-center px-0.5">
                                <span class="{{ $colorTimepicker['column_label'] ?? 'text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500' }} mb-2 invisible">&nbsp;</span>
                                <div class="h-9"></div>
                                <div class="h-12 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-300 dark:text-gray-600 select-none">:</span>
                                </div>
                            </div>

                            {{-- Second wheel --}}
                            <div class="flex flex-col items-center"
                                @wheel.prevent="wheelSecond($event)"
                                @keydown.up.prevent="moveSecond(-1)"
                                @keydown.down.prevent="moveSecond(1)"
                                tabindex="0"
                                role="listbox"
                                aria-label="{{ __('beartropy-ui::ui.second_short') }}"
                            >
                                <span class="{{ $colorTimepicker['column_label'] ?? 'text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500' }} mb-2">{{ __('beartropy-ui::ui.second_short') }}</span>
                                <button type="button" @click="moveSecond(-1)"
                                    class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorTimepicker['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                    :class="second === null && 'invisible'" x-text="getAdjacentSecond(-1)" tabindex="-1"
                                ></button>
                                <div class="h-12 w-14 flex items-center justify-center rounded-xl {{ $colorTimepicker['wheel_highlight'] ?? 'bg-beartropy-50 dark:bg-beartropy-950/30' }}">
                                    <span class="text-[1.75rem] font-bold tabular-nums leading-none {{ $colorTimepicker['wheel_selected'] ?? 'text-beartropy-600 dark:text-beartropy-400' }}" x-text="second ?? '--'"></span>
                                </div>
                                <button type="button" @click="moveSecond(1)"
                                    class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorTimepicker['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                    :class="second === null && 'invisible'" x-text="getAdjacentSecond(1)" tabindex="-1"
                                ></button>
                            </div>
                        @endif

                        @if($is12h)
                            {{-- AM/PM toggle --}}
                            <div class="flex flex-col items-center pl-3">
                                <span class="{{ $colorTimepicker['column_label'] ?? 'text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500' }} mb-2 invisible">&nbsp;</span>
                                <div class="flex flex-col items-center justify-center gap-1.5 h-[7.5rem]">
                                    <button type="button"
                                        @click="togglePeriod('AM')"
                                        class="{{ $colorTimepicker['ampm_button'] ?? 'px-3 py-1.5 rounded-lg text-xs font-bold tracking-wide transition-all duration-150 cursor-pointer' }}"
                                        :class="period === 'AM'
                                            ? '{{ $colorTimepicker['ampm_active'] ?? 'bg-beartropy-500 text-white shadow-sm' }}'
                                            : 'text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 dark:hover:text-gray-300'"
                                    >AM</button>
                                    <button type="button"
                                        @click="togglePeriod('PM')"
                                        class="{{ $colorTimepicker['ampm_button'] ?? 'px-3 py-1.5 rounded-lg text-xs font-bold tracking-wide transition-all duration-150 cursor-pointer' }}"
                                        :class="period === 'PM'
                                            ? '{{ $colorTimepicker['ampm_active'] ?? 'bg-beartropy-500 text-white shadow-sm' }}'
                                            : 'text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 dark:hover:text-gray-300'"
                                    >PM</button>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Now button --}}
                    <div class="px-4 py-2.5 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-center">
                        <button type="button"
                            @click="setNow()"
                            class="{{ $colorTimepicker['now_button'] ?? 'text-xs font-semibold tracking-wide uppercase cursor-pointer transition-colors duration-150' }}"
                        >{{ __('beartropy-ui::ui.now') }}</button>
                    </div>
                </div>
            </x-beartropy-ui::base.dropdown-base>
        </x-slot>
    </x-beartropy-ui::base.input-trigger-base>

    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />

    @unless($hasWireModel)
        <input type="hidden" name="{{ $inputName }}" :value="value">
    @endunless
</div>
