@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('input');
    [$colorDropdown, $sizeDropdown] = $getComponentPresets('datetime'); // Reusing datetime preset for now
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $pickerId = $attributes->get('id') ?? 'timepicker-' . uniqid();
    $label = $label ?? null;
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];
    $name = $wireModelValue ?: ($name ?? $pickerId);
    $placeholder = $placeholder ?? '00:00';

    $wrapperClass = $attributes->get('class') ?? '';
@endphp

<div
    x-data="$beartropy.timepicker(
        @if($hasWireModel)
            @entangle($attributes->wire('model'))
        @else
            @js($value)
        @endif,
        '{{ $format }}'
    )"
    x-init="init()"
    class="flex flex-col w-full {{ $wrapperClass }}"
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
        {{ $attributes->only(['fill', 'outline']) }}
    >
        <x-slot name="button">
            <div @click="open = !open" class="flex items-center gap-2 min-h-[1.6em] cursor-pointer w-full truncate ">
                <span x-text="displayLabel || '{{ $placeholder }}'"
                    :class="!displayLabel ? 'beartropy-placeholder text-sm text-gray-400 dark:text-gray-500' : '{{$colorDropdown['option_text']}}'"></span>
            </div>
        </x-slot>
        <x-slot name="end">
            @if($clearable ?? true)
                <span
                    x-show="value"
                    @click.stop="clear()"
                    class="mr-1 cursor-pointer text-neutral-400 hover:text-red-500 transition"
                    title="Limpiar"
                >
                    @include('beartropy-ui-svg::beartropy-x-mark', [
                        'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 ' . ($sizePreset['iconSize'] ?? '')
                    ])
                </span>
            @endif
            <span @click="open = !open" class="cursor-pointer w-full">
                @include('beartropy-ui-svg::beartropy-clock', [
                     'class' => 'w-5 h-5 pl-1 transition-transform duration-200 text-gray-500 dark:text-gray-300'
                ])
            </span>
        </x-slot>

        <x-slot name="dropdown">
            <x-beartropy-ui::base.dropdown-base
                placement="bottom-start"
                side="bottom"
                color="{{ $presetNames['color'] }}"
                preset-for="datetime"
                width="w-auto"
                x-show="open"
                triggerLabel="{{ $label }}"
                x-transition
            >
                <div class="p-3 select-none bg-transparent" x-init="$watch('open', value => value && scrollToSelected())">
                    <div class="{{ $colorDropdown['list_wrapper'] ?? 'flex items-start justify-center gap-2 h-56' }}">
                        <!-- Hora -->
                        <div class="flex flex-col items-center h-full">
                            <label class="text-xs text-gray-500 mb-1 font-medium">Hora</label>
                            <ul x-ref="hoursColumn" class="{{ $colorDropdown['list_column'] ?? 'flex flex-col h-full overflow-y-auto beartropy-thin-scrollbar w-16 text-center scroll-smooth' }}">
                                <template x-for="h in 24" :key="h">
                                    <li
                                        @click="hour = String(h-1).padStart(2,'0'); updateTime()"
                                        :data-value="String(h-1).padStart(2,'0')"
                                        class="{{ $colorDropdown['list_item'] ?? 'py-1 px-2 cursor-pointer rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 transition-colors' }}"
                                        :class="{ '{{ $colorDropdown['list_item_active'] ?? 'bg-beartropy-500 text-white font-bold hover:bg-beartropy-600 dark:hover:bg-beartropy-600' }}': hour === String(h-1).padStart(2,'0') }"
                                        x-text="String(h-1).padStart(2,'0')"
                                    ></li>
                                </template>
                            </ul>
                        </div>

                        <span class="text-xl font-bold opacity-30 pt-8">:</span>

                        <!-- Minuto -->
                        <div class="flex flex-col items-center h-full">
                            <label class="text-xs text-gray-500 mb-1 font-medium">Min</label>
                            <ul x-ref="minutesColumn" class="{{ $colorDropdown['list_column'] ?? 'flex flex-col h-full overflow-y-auto beartropy-thin-scrollbar w-16 text-center scroll-smooth' }}">
                                <template x-for="m in 60" :key="m">
                                    <li
                                        @click="minute = String(m-1).padStart(2,'0'); updateTime()"
                                        :data-value="String(m-1).padStart(2,'0')"
                                        class="{{ $colorDropdown['list_item'] ?? 'py-1 px-2 cursor-pointer rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 transition-colors' }}"
                                        :class="{ '{{ $colorDropdown['list_item_active'] ?? 'bg-beartropy-500 text-white font-bold hover:bg-beartropy-600 dark:hover:bg-beartropy-600' }}': minute === String(m-1).padStart(2,'0') }"
                                        x-text="String(m-1).padStart(2,'0')"
                                    ></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </x-beartropy-ui::base.dropdown-base>
        </x-slot>
    </x-beartropy-ui::base.input-trigger-base>

    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
