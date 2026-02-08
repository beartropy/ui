@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('input');
    [$colorDatetime] = $getComponentPresets('datetime');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $pickerId = $id;
    $inputName = $wireModelValue ?: $name;
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];
    $placeholderText = $placeholder ?? ($range ? __('beartropy-ui::ui.select_range') : __('beartropy-ui::ui.select_date'));
    $wrapperClass = $attributes->get('class') ?? '';
@endphp

<div
    x-data="beartropyDatetimepicker({
        value: @if($hasWireModel) @entangle($attributes->wire('model')) @else @js($value) @endif,
        range: {{ $range ? 'true' : 'false' }},
        min: @js($min ?? ''),
        max: @js($max ?? ''),
        formatDisplay: @js($formatDisplay),
        showTime: {{ $showTime ? 'true' : 'false' }},
        disabled: {{ $disabled ? 'true' : 'false' }},
        i18n: { now: '{{ __('beartropy-ui::ui.now') }}', today: '{{ __('beartropy-ui::ui.today') }}', changeDate: '{{ __('beartropy-ui::ui.change_date') }}' },
    })"
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
                    :class="!displayLabel ? 'beartropy-placeholder text-sm text-gray-400 dark:text-gray-500' : '{{ $colorDatetime['option_text'] ?? 'text-neutral-800 dark:text-neutral-100' }}'"></span>
            </div>
        </x-slot>

        <x-slot name="end">
            <div class="flex items-center gap-0.5 px-2">
                @if($clearable)
                    <button
                        type="button"
                        x-show="value"
                        @click.stop="clearSelection()"
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
                    <svg class="w-5 h-5 transition-transform duration-200 text-gray-400 dark:text-gray-500"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </button>
            </div>
        </x-slot>

        <x-slot name="dropdown">
            <x-beartropy-ui::base.dropdown-base
                placement="right"
                side="bottom"
                color="{{ $presetNames['color'] }}"
                preset-for="datetime"
                width="w-full max-w-[25rem]"
                overflow="visible"
                x-show="open"
                triggerLabel="{{ $label }}"
                x-transition
            >
                {{-- Calendar pane --}}
                <div x-show="showCalendarPane()" class="p-3 select-none bg-transparent">
                    {{-- Header: Month and year --}}
                    <div class="flex items-center justify-between mb-2 gap-2 {{ $colorDatetime['header_text'] ?? '' }}">
                        <button type="button" @click="prevMonth()" class="p-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor">
                                <path stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <span class="select-none" x-text="`${year}-${(month+1).toString().padStart(2,'0')}`"></span>
                        <button type="button" @click="nextMonth()" class="p-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor">
                                <path stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                    {{-- Days of the week --}}
                    <div class="grid grid-cols-7 text-xs text-center mb-1 {{ $colorDatetime['weekday_text'] ?? '' }}">
                        <span>{{ __('beartropy-ui::ui.day_mon') }}</span><span>{{ __('beartropy-ui::ui.day_tue') }}</span><span>{{ __('beartropy-ui::ui.day_wed') }}</span><span>{{ __('beartropy-ui::ui.day_thu') }}</span><span>{{ __('beartropy-ui::ui.day_fri') }}</span><span>{{ __('beartropy-ui::ui.day_sat') }}</span><span>{{ __('beartropy-ui::ui.day_sun') }}</span>
                    </div>
                    {{-- Days grid --}}
                    <div class="grid grid-cols-7 text-center {{ $colorDatetime['grid_bg'] ?? '' }}">
                        <template x-for="(day, i) in days" :key="i">
                            <button type="button" @click="selectDay(day)"
                                @mouseenter="if (range && !end && start) hovered = day.date"
                                @mouseleave="if (range && !end && start) hovered = null"
                                :disabled="isDisabled(day)"
                                :class="{
                                    '{{ $colorDatetime['option_active'] ?? '' }}': isSelected(day),
                                    '{{ $colorDatetime['option_range'] ?? 'bg-beartropy-100 dark:bg-beartropy-800' }}': isInRange(day),
                                    '{{ $colorDatetime['today_ring'] ?? '' }}': isToday(day) && !isSelected(day),
                                    '{{ $colorDatetime['option_hover'] ?? '' }} rounded-lg': day.inMonth && !isSelected(day) && !isInRange(day) && !isDisabled(day),
                                    'opacity-40 cursor-not-allowed': isDisabled(day),
                                    'rounded-l-lg': range && start && day.date === start,
                                    'rounded-r-lg': range && end && day.date === end,
                                    'rounded-lg': !range,
                                }"
                                class="w-full text-center py-1 transition font-medium {{ $colorDatetime['option_text'] ?? '' }}"
                                x-text="day.label"></button>
                        </template>
                    </div>
                    {{-- Today button --}}
                    <div class="pt-2 flex items-center justify-center">
                        <button type="button" @click="goToToday()"
                            class="{{ $colorDatetime['now_button'] ?? 'text-xs font-semibold tracking-wide uppercase cursor-pointer transition-colors duration-150' }}">
                            {{ __('beartropy-ui::ui.today') }}
                        </button>
                    </div>
                </div>

                {{-- Header when calendar is hidden (showTime mode) --}}
                <div x-show="showTime && !showCalendarPane()"
                    class="p-3 pb-2 flex items-center justify-between {{ $colorDatetime['header_text'] ?? '' }}">
                    <span class="text-sm font-medium"
                        x-text="formatForDisplay(panel === 'time-end' && end ? end : start, formatDisplay)"></span>
                    <button type="button" @click="panel = panel === 'time-end' ? 'date-end' : 'date-start'; hovered = null"
                        class="text-xs px-2 py-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                        {{ __('beartropy-ui::ui.change_date') }}
                    </button>
                </div>

                {{-- Time wheel section --}}
                @if($showTime)
                <div x-show="isPickingStartTime() || isPickingEndTime()" class="select-none">
                    <div class="flex items-start justify-center px-5 pt-4 pb-3">
                        {{-- Hour wheel --}}
                        <div class="flex flex-col items-center"
                            @wheel.prevent="wheelHour(currentTimeType(), $event)"
                            @keydown.up.prevent="moveHour(currentTimeType(), -1)"
                            @keydown.down.prevent="moveHour(currentTimeType(), 1)"
                            tabindex="0"
                            role="listbox"
                            aria-label="{{ __('beartropy-ui::ui.hour') }}"
                        >
                            <span class="{{ $colorDatetime['column_label'] ?? 'text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500' }} mb-2">{{ __('beartropy-ui::ui.hour') }}</span>
                            <button type="button" @click="moveHour(currentTimeType(), -1)"
                                class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorDatetime['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                x-text="getAdjacentHour(currentTimeType(), -1)" tabindex="-1"
                            ></button>
                            <div class="h-12 w-14 flex items-center justify-center rounded-xl {{ $colorDatetime['wheel_highlight'] ?? 'bg-beartropy-50 dark:bg-beartropy-950/30' }}">
                                <span class="text-[1.75rem] font-bold tabular-nums leading-none {{ $colorDatetime['wheel_selected'] ?? 'text-beartropy-600 dark:text-beartropy-400' }}" x-text="getHourForType(currentTimeType())"></span>
                            </div>
                            <button type="button" @click="moveHour(currentTimeType(), 1)"
                                class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorDatetime['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                x-text="getAdjacentHour(currentTimeType(), 1)" tabindex="-1"
                            ></button>
                        </div>

                        {{-- Separator --}}
                        <div class="flex flex-col items-center px-0.5">
                            <span class="{{ $colorDatetime['column_label'] ?? 'text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500' }} mb-2 invisible">&nbsp;</span>
                            <div class="h-9"></div>
                            <div class="h-12 flex items-center justify-center">
                                <span class="text-2xl font-bold text-gray-300 dark:text-gray-600 select-none">:</span>
                            </div>
                        </div>

                        {{-- Minute wheel --}}
                        <div class="flex flex-col items-center"
                            @wheel.prevent="wheelMinute(currentTimeType(), $event)"
                            @keydown.up.prevent="moveMinute(currentTimeType(), -1)"
                            @keydown.down.prevent="moveMinute(currentTimeType(), 1)"
                            tabindex="0"
                            role="listbox"
                            aria-label="{{ __('beartropy-ui::ui.minute_short') }}"
                        >
                            <span class="{{ $colorDatetime['column_label'] ?? 'text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500' }} mb-2">{{ __('beartropy-ui::ui.minute_short') }}</span>
                            <button type="button" @click="moveMinute(currentTimeType(), -1)"
                                class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorDatetime['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                x-text="getAdjacentMinute(currentTimeType(), -1)" tabindex="-1"
                            ></button>
                            <div class="h-12 w-14 flex items-center justify-center rounded-xl {{ $colorDatetime['wheel_highlight'] ?? 'bg-beartropy-50 dark:bg-beartropy-950/30' }}">
                                <span class="text-[1.75rem] font-bold tabular-nums leading-none {{ $colorDatetime['wheel_selected'] ?? 'text-beartropy-600 dark:text-beartropy-400' }}" x-text="getMinuteForType(currentTimeType())"></span>
                            </div>
                            <button type="button" @click="moveMinute(currentTimeType(), 1)"
                                class="h-9 w-14 flex items-center justify-center text-lg tabular-nums transition-colors {{ $colorDatetime['wheel_adjacent'] ?? 'text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500' }}"
                                x-text="getAdjacentMinute(currentTimeType(), 1)" tabindex="-1"
                            ></button>
                        </div>
                    </div>

                    {{-- Now button --}}
                    <div class="px-4 py-2.5 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-center">
                        <button type="button" @click="setTimeNow(currentTimeType())"
                            class="{{ $colorDatetime['now_button'] ?? 'text-xs font-semibold tracking-wide uppercase cursor-pointer transition-colors duration-150' }}">
                            {{ __('beartropy-ui::ui.now') }}
                        </button>
                    </div>
                </div>
                @endif
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
