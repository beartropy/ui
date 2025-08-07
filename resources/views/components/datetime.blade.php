@php
    [$colorPreset, $sizePreset, $presetNames] = $getComponentPresets('input');
    [$colorDropdown, $sizeDropdown] = $getComponentPresets('datetime');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $pickerId = $attributes->get('id') ?? 'datepicker-' . uniqid();
    $label = $label ?? null;
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];
    $name = $wireModelValue ?: ($name ?? $selectId);
    $placeholder = $placeholder
        ?? (
            ($range ?? false)
                ? (($locale ?? 'es') === 'es' ? 'Seleccionar rango…' : 'Select range…')
                : (($locale ?? 'es') === 'es' ? 'Seleccionar fecha…' : 'Select date…')
        );

@endphp

<div
    x-data="$beartropy.datetimepicker(
        @if($hasWireModel)
            @entangle($attributes->wire('model'))
        @else
            @js($value)
        @endif,
        {{ ($range ?? false) ? 'true' : 'false' }},
        '{{ $min ?? '' }}',
        '{{ $max ?? '' }}',
        '{{ $formatDisplay }}',
        {{ ($showTime ?? false) ? 'true' : 'false' }},
    )"
    x-init="init()"
    class="flex flex-col w-full"
>
    @if($label)
        <label for="{{ $pickerId }}" class="{{ $labelClass }}">
            {{ $label }}
        </label>
    @endif

    <x-base.input-trigger-base
        id="{{ $pickerId }}"
        color="{{ $presetNames['color'] }}"
        size="{{ $presetNames['size'] }}"
        custom-error="{{ $customError }}"
        hint="{{ $hint }}"
        has-error="{{ $hasError }}"
    >
        <x-slot name="button">
            <div @click="open = !open" class="flex items-center gap-2 min-h-[1.6em] cursor-pointer w-full truncate ">
                <span x-text="displayLabel || '{{ $placeholder }}'"
                    :class="!displayLabel ? 'beartropy-placeholder' : '{{$colorDropdown['option_text']}}'"></span>

            </div>
        </x-slot>
        <x-slot name="end">
            @if($clearable ?? true)
                <span
                    x-show="value"
                    @click.stop="
                        value = '';
                        start = '';
                        end = '';
                        startHour = '00';
                        startMinute = '00';
                        endHour = '00';
                        endMinute = '00';
                        displayLabel = '';
                    "
                    class="mr-1 cursor-pointer text-neutral-400 hover:text-red-500 transition"
                    title="Limpiar"
                >
                    @include('beartropy-ui-svg::beartropy-x-mark', [
                        'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 ' . ($sizePreset['iconSize'] ?? '')
                    ])
                </span>
            @endif
            <span @click="open = !open" class="cursor-pointer w-full">
                <svg class="w-5 h-5 pl-1 transition-transform duration-200 text-gray-500 dark:text-gray-300"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </x-slot>

        <x-slot name="dropdown">
            <x-base.dropdown-base
                placement="right"
                side="bottom"
                color="{{ $presetNames['color'] }}"
                preset-for="select"
                width="w-full max-w-[25rem]"
                x-show="open"
                x-transition
            >
                <div class="p-3 select-none bg-transparent">
                    <!-- Header: Mes y año -->
                    <div class="flex items-center justify-between mb-2 gap-2 {{ $colorDropdown['header_text'] ?? '' }}">
                        <button @click="prevMonth()" class="p-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <span class="select-none" x-text="`${year}-${(month+1).toString().padStart(2,'0')}`"></span>
                        <button @click="nextMonth()" class="p-1 rounded hover:bg-neutral-100 dark:hover:bg-neutral-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                    <!-- Días de la semana -->
                    <div class="grid grid-cols-7 text-xs text-center mb-1 {{ $colorDropdown['weekday_text'] ?? '' }}">
                        <span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
                    </div>
                    <!-- Días -->
                    <div class="grid grid-cols-7 text-center {{ $colorDropdown['grid_bg'] ?? '' }}">
                        <template x-for="(day, i) in days" :key="i">
                            <button
                                type="button"
                                @click="selectDay(day)"
                                @mouseenter="if (range && !end && start) hovered = day.date"
                                @mouseleave="if (range && !end && start) hovered = null"
                                :disabled="isDisabled(day)"
                                :class="{
                                    '{{ $colorDropdown['option_active'] ?? '' }}': isSelected(day),
                                    '{{ $colorDropdown['option_range'] ?? 'bg-beartropy-100 dark:bg-beartropy-800' }}': isInRange(day),
                                    '{{ $colorDropdown['option_hover'] ?? '' }} rounded-lg': day.inMonth && !isSelected(day) && !isInRange(day) && !isDisabled(day),
                                    'opacity-40 cursor-not-allowed': isDisabled(day),
                                    'rounded-l-lg': range && start && day.date === start,
                                    'rounded-r-lg': range && end && day.date === end,
                                    'rounded-lg': !range,
                                }"
                                class="w-full text-center py-1 transition font-medium {{ $colorDropdown['option_text'] ?? '' }}"
                                x-text="day.label"
                            ></button>
                        </template>
                    </div>
                </div>
                <!-- Selección de hora/minuto -->
                <div class="flex w-full items-center justify-center gap-4 mb-2 bg-transparent">
                    <template x-if="showTime && start && (!range || (range && !end))">
                        <div class="flex items-center gap-2 justify-center ">
                            <!-- Hora inicio -->
                            <div class="relative">
                                <select
                                    x-model="startHour"
                                    class="{{ $colorDropdown['select'] ?? '' }} beartropy-thin-scrollbar"
                                >
                                    <template x-for="h in 24" :key="h">
                                        <option :value="String(h-1).padStart(2,'0')" x-text="String(h-1).padStart(2,'0')"></option>
                                    </template>
                                </select>
                            </div>
                            <span class="text-lg opacity-60">:</span>
                            <!-- Minuto inicio -->
                            <div class="relative">
                                <select
                                    x-model="startMinute"
                                    @change="setTime('start', startHour, startMinute)"
                                    class="{{ $colorDropdown['select'] ?? '' }} beartropy-thin-scrollbar"
                                >
                                    <template x-for="m in 60" :key="m">
                                        <option :value="String(m-1).padStart(2,'0')" x-text="String(m-1).padStart(2,'0')"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </template>
                    <template x-if="showTime && end">
                        <div class="flex items-center gap-2 justify-center">
                            <!-- Hora fin -->
                            <div class="relative">
                                <select
                                    x-model="endHour"
                                    class="{{ $colorDropdown['select'] ?? '' }} beartropy-thin-scrollbar"
                                >
                                    <template x-for="h in 24" :key="h">
                                        <option :value="String(h-1).padStart(2,'0')" x-text="String(h-1).padStart(2,'0')"></option>
                                    </template>
                                </select>
                            </div>
                            <span class="text-lg opacity-60">:</span>
                            <!-- Minuto fin -->
                            <div class="relative">
                                <select
                                    x-model="endMinute"
                                    @change="setTime('end', endHour, endMinute)"
                                    class="{{ $colorDropdown['select'] ?? '' }} beartropy-thin-scrollbar"
                                >
                                    <template x-for="m in 60" :key="m">
                                        <option :value="String(m-1).padStart(2,'0')" x-text="String(m-1).padStart(2,'0')"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </template>
                </div>


            </x-base.dropdown-base>
        </x-slot>
    </x-base.input-trigger-base>

    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
