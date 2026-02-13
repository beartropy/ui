@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('input');
    [$colorDropdown, $sizeDropdown] = $getComponentPresets('select');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $selectId = $attributes->get('id') ?? 'select-' . uniqid();

    // Drain slot options collected by <x-bt-option> children
    $slotOptions = \Beartropy\Ui\Components\Select::$pendingSlotOptions;
    \Beartropy\Ui\Components\Select::$pendingSlotOptions = [];
    $options = $options ?? [];

    if (!empty($slotOptions)) {
        foreach ($slotOptions as $opt) {
            $options[(string) $opt['_value']] = $opt;
        }
        if ($isEmpty) {
            $isEmpty = false;
            $searchable = $userSearchable;
            $clearable = $userClearable;
        }
    }

    $label = $label ?? null;
    $placeholder = $placeholder ?? __('beartropy-ui::ui.select');
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];

    $isMulti = ($multiple ?? false);
    $name = $wireModelValue ?: ($name ?? $selectId);
    $parsedInitial = $isMulti
        ? (is_array($initialValue) ? $initialValue : (empty($initialValue) ? [] : [$initialValue]))
        : ($hasWireModel ? '' : ($initialValue ?? ''));

    $remoteUrl = $remoteUrl ?? null;
    $perPage = $perPage ?? 15;

    $wrapperClass = $attributes->get('class') ?? '';

    $autosave          = $autosave          ?? false;
    $autosaveMethod    = $autosaveMethod    ?? 'savePreference';
    $autosaveKey       = $autosaveKey       ?? ($wireModelValue ?? null);
    $autosaveDebounce  = $autosaveDebounce  ?? 300;

    if($hasWireModel && $spinner && !$autosave) {
        $showSpinner = true;
    }

@endphp
<div
    x-data="$beartropy.beartropySelect({
        value: @if($hasWireModel) $wire.get('{{ $name }}') @else {{ $isMulti ? json_encode($parsedInitial) : "'" . $parsedInitial . "'" }} @endif,
        options: @js($options),
        isMulti: {{ $isMulti ? 'true' : 'false' }},
        perPage: {{ $perPage }},
        remoteUrl: '{{ $remoteUrl }}',
        autosave: {{ $autosave ? 'true' : 'false' }},
        autosaveMethod: '{{ $autosaveMethod }}',
        autosaveKey: '{{ $autosaveKey }}',
        autosaveDebounce: {{ $autosaveDebounce }},
        hasFieldError: {{ $hasError ? 'true' : 'false' }},
        showSpinner: {{ isset($showSpinner) && $showSpinner ? 'true' : 'false' }},
        hasWireModel: {{ $hasWireModel ? 'true' : 'false' }},
        name: '{{ $name }}',
        selectId: '{{ $selectId }}',
        defer: {{ $defer ? 'true' : 'false' }},
    })"
    class="flex flex-col w-full {{ $wrapperClass }}"
    wire:key="{{ $selectId }}"
    @keydown.arrow-down.prevent="if(!open) { toggle() } else { move(1) }"
    @keydown.arrow-up.prevent="if(!open) { toggle() } else { move(-1) }"
    @keydown.enter.prevent="if(open && highlightedIndex >= 0) { selectHighlighted() } else if(!open) { toggle() }"
    @keydown.escape.prevent="close()"
    @keydown.space="if(!open && !$event.target.matches('input')) { $event.preventDefault(); toggle() }"
>
    @if($label)
        <label for="{{ $selectId }}" class="{{ $labelClass }}">{{ $label }}</label>
    @endif

    @if(!$hasWireModel)
        <span x-ref="multiInputs"></span>
    @endif

    <x-beartropy-ui::base.input-trigger-base
        id="{{ $selectId }}"
        color="{{ $presetNames['color'] }}"
        size="{{ $presetNames['size'] }}"
        custom-error="{{ $customError }}"
        hint="{{ $hint }}"
        has-error="{{ $hasError }}"
        {{ $attributes->only(['fill', 'outline']) }}
        x-bind:data-state="hasFieldError ? 'error' : saveState"
        x-bind:style="(() => {
            const s = hasFieldError ? 'error' : saveState;
            if (s === 'ok')     return 'border-color: #10b981; box-shadow: 0 0 0 1px #34d399; transition: border-color .2s, box-shadow .2s';
            if (s === 'error')  return 'border-color: #ef4444; box-shadow: 0 0 0 1px #f87171; transition: border-color .2s, box-shadow .2s';
            if (s === 'saving') return 'border-color: #9ca3af; box-shadow: 0 0 0 1px rgba(156,163,175,.5); transition: border-color .2s, box-shadow .2s';
            return '';
        })()"
        bind="open"
    >
        @isset($start)
            <x-slot name="start">{!! $start !!}</x-slot>
        @endisset

        <x-slot name="button">
            <div
                @click="toggle()"
                tabindex="0"
                class="relative flex flex-wrap items-center gap-1 min-h-[1.6em] cursor-pointer w-full pr-2 md:pr-3 {{ $colorDropdown['option_text'] ?? '' }}"
            >
                {{-- MULTI SELECT: truncated chips --}}
                <template x-if="isMulti && value && value.length">
                    <template x-for="(id, idx) in visibleChips()" :key="id">
                        <span
                            class="inline-flex items-center gap-1 rounded px-2 py-0.5 text-sm beartropy-select-chip {{ $colorDropdown['chip_bg'] ?? '' }} {{ $colorDropdown['chip_text'] ?? '' }}"
                            :title="options[id]?.description ?? ''"
                        >
                            <!-- Avatar -->
                            <template x-if="options[id]?.avatar">
                                <span class="inline-flex w-5 h-5 rounded-full overflow-hidden justify-center items-center bg-neutral-200 dark:bg-neutral-700">
                                    <img :src="options[id].avatar" alt="" class="w-5 h-5 object-cover" x-show="options[id].avatar && options[id].avatar.startsWith('http')" />
                                    <span x-show="options[id].avatar && !options[id].avatar.startsWith('http')" x-text="options[id].avatar" class="text-base"></span>
                                </span>
                            </template>
                            <!-- Icon, only if no avatar -->
                            <template x-if="!options[id]?.avatar && options[id]?.icon">
                                <span class="inline-flex w-5 h-5 justify-center items-center">
                                    <span x-html="options[id].icon" class="text-base [&>svg]:w-4 [&>svg]:h-4"></span>
                                </span>
                            </template>
                            <!-- Label -->
                            <span x-text="options[id]?.label ?? options[id] ?? id"></span>
                            <!-- Remove button -->
                            <button type="button" @click.stop="removeSelected(id)" class="ml-1 {{ $colorDropdown['chip_close'] ?? '' }}">&times;</button>
                        </span>

                    </template>
                </template>

                {{-- Badge +N --}}
                <span
                    x-cloak
                    x-show="isMulti && value && hiddenCount()"
                    class="inline-flex items-center rounded px-2 py-0.5 text-xs font-semibold beartropy-select-badge {{ $colorDropdown['badge_bg'] ?? '' }} {{ $colorDropdown['badge_text'] ?? '' }}"
                >
                    +<span x-text="hiddenCount()"></span>
                </span>

                {{-- Placeholder --}}
                <span
                    x-show="!((isMulti && value && value.length) || (!isMulti && value))"
                    class="beartropy-placeholder text-sm text-gray-400 dark:text-gray-500"
                >{{ $placeholder }}</span>

                {{-- SINGLE SELECT: label --}}
                <span
                    x-show="!isMulti && value"
                    class="flex items-center gap-2 truncate {{ $colorDropdown['option_text'] ?? '' }}"
                    :title="options[value]?.description ?? ''"
                >
                    <!-- Avatar -->
                    <template x-if="options[value]?.avatar">
                        <span class="inline-flex w-5 h-5 rounded-full overflow-hidden justify-center items-center bg-neutral-200 dark:bg-neutral-700">
                            <img :src="options[value].avatar"
                                alt=""
                                class="w-5 h-5 object-cover"
                                x-show="options[value].avatar && options[value].avatar.startsWith('http')" />
                            <span x-show="options[value].avatar && !options[value].avatar.startsWith('http')"
                                x-text="options[value].avatar"
                                class="text-base"></span>
                        </span>
                    </template>
                    <!-- Icon, only if no avatar -->
                    <template x-if="!options[value]?.avatar && options[value]?.icon">
                        <span class="inline-flex w-5 h-5 justify-center items-center">
                            <span x-html="options[value].icon" class="text-base [&>svg]:w-4 [&>svg]:h-4"></span>
                        </span>
                    </template>
                    <!-- Label -->
                    <span x-text="options[value]?.label ?? options[value] ?? value"></span>
                </span>
            </div>
        </x-slot>


        <x-slot name="end">
            <div class="flex items-center gap-0.5 px-2">
                @if($clearable)
                    <button
                        type="button"
                        @click.stop="clearValue()"
                        class="grid place-items-center w-5 h-5 rounded transition hover:bg-gray-100 dark:hover:bg-white/5"
                        :class="((isMulti && value && value.length) || (!isMulti && value))
                            ? 'opacity-100 pointer-events-auto'
                            : 'opacity-0 pointer-events-none'"
                        title="{{ __('beartropy-ui::ui.clear_selection') }}"
                        aria-label="{{ __('beartropy-ui::ui.clear_selection') }}"
                        x-cloak
                    >
                        @include('beartropy-ui-svg::beartropy-x-mark', [
                            'class' => 'shrink-0 text-gray-700 dark:text-gray-400 ' . ($sizePreset['iconSize'] ?? '')
                        ])
                    </button>
                @endif

                {{-- Autosave indicator: between clear and chevron --}}
                <template x-if="autosave && saveState !== 'idle'">
                    <span class="grid place-items-center w-5 h-5">
                        <!-- saving -->
                        <svg x-show="saveState==='saving'" class="w-4 h-4 animate-spin text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity=".25"/>
                            <path d="M21 12a9 9 0 0 1-9 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <!-- ok -->
                        <svg x-show="saveState==='ok'" class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <!-- error -->
                        <svg x-show="saveState==='error'" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                </template>

                {{-- Spinner Livewire --}}
                <template x-if="showSpinner">
                    <span class="grid place-items-center w-5 h-5" wire:loading wire:target="{{ $wireModelValue }}">
                        <svg class="w-4 h-4 animate-spin text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity=".25"/>
                            <path d="M21 12a9 9 0 0 1-9 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                </template>

                {{-- Chevron always fixed at right edge --}}
                <button
                    type="button"
                    @click="toggle()"
                    class="grid place-items-center w-5 h-5 shrink-0"
                >
                    <svg
                        class="w-4.5 h-4.5 transition-transform duration-200 {{ $colorDropdown['option_icon'] ?? '' }}"
                        :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
        </x-slot>



        <x-slot name="dropdown">
            <div wire:key="{{ $selectId }}-err-{{ $hasError }}">
            <x-beartropy-ui::base.dropdown-base
                placement="left"
                side="bottom"
                color="{{$presetNames['color']}}"
                preset-for="select"
                width="{{ $fitTrigger ? 'w-full' : 'min-w-full' }}"
                :fit-anchor="$fitTrigger"
                x-show="open"
                triggerLabel="{{ $label }}"
                @click.away="close()"
                wire:key="{{ $selectId }}-dropdown"
                :teleport="$teleport ?? true"
            >
                @if($searchable)
                    {{-- Search input --}}
                    <div class="p-2" x-ref="searchHost">
                        <x-beartropy-ui::input
                            type="text"
                            placeholder="{{ __('beartropy-ui::ui.search') }}"
                            x-model="search"
                            autocomplete="off"
                            color="{{$presetNames['color']}}"
                            size="{{$presetNames['size']}}"
                            id="{{ $selectId }}-search"
                            icon-end="magnifying-glass"
                            data-beartropy-input
                        />
                    </div>
                @endif
                {{-- Options list --}}
                <ul
                    id="{{ $selectId }}-list"
                    role="listbox"
                    class="overflow-y-auto max-h-60 divide-y divide-gray-100 dark:divide-gray-800 beartropy-thin-scrollbar"
                    @scroll="if($event.target.scrollTop + $event.target.clientHeight >= $event.target.scrollHeight - 10 && hasMore && !loading) { page++; fetchOptions(); }"
                >
                    @if(isset($beforeOptions))
                        <div class="{{$beforeOptions->attributes->get('class') ?? 'p-2'}}">
                            {!! $beforeOptions !!}
                        </div>
                    @endif
                    <template
                        x-for="([id, option], idx) in filteredOptions()"
                        :key="id"
                    >
                        <li
                            role="option"
                            :aria-selected="isSelected(id)"
                            :data-select-index="idx"
                            @mouseenter="highlightedIndex = idx"
                        >
                            <button
                                type="button"
                                @click="setValue(id)"
                                class="w-full text-left px-4 py-2 flex items-center gap-2 {{ $colorDropdown['option_text'] ?? '' }} {{ $colorDropdown['option_hover'] ?? '' }}"
                                :class="{
                                    '{{ $colorDropdown['option_active'] ?? '' }} {{ $colorDropdown['option_selected'] ?? '' }}': isSelected(id),
                                    '{{ $colorDropdown['option_active'] ?? 'bg-neutral-100 dark:bg-neutral-800' }}': idx === highlightedIndex && !isSelected(id),
                                }"
                            >
                                <div class="flex flex-col items-start w-full">
                                    <div class="flex items-center gap-2">
                                        <!-- Avatar -->
                                        <template x-if="option.avatar">
                                            <span class="inline-flex w-6 h-6 rounded-full overflow-hidden justify-center items-center bg-neutral-200 dark:bg-neutral-700">
                                                <img :src="option.avatar" alt="" class="w-6 h-6 object-cover" x-show="option.avatar && option.avatar.startsWith('http')" />
                                                <span x-show="option.avatar && !option.avatar.startsWith('http')" x-text="option.avatar" class="text-lg"></span>
                                            </span>
                                        </template>
                                        <!-- Icon, only if no avatar -->
                                        <template x-if="!option.avatar && option.icon">
                                            <span class="inline-flex w-6 h-6 justify-center items-center">
                                                <span x-html="option.icon" class="text-lg [&>svg]:w-5 [&>svg]:h-5"></span>
                                            </span>
                                        </template>
                                        <!-- Label -->
                                        <span class="truncate font-medium" x-text="option.label ?? option ?? id"></span>
                                    </div>
                                    <!-- Description -->
                                    <template x-if="option.description">
                                        <span class="{{ $colorDropdown['desc_text'] ?? 'text-xs text-neutral-500 dark:text-neutral-400 mt-0.5' }}"
                                            x-text="option.description"></span>
                                    </template>
                                </div>
                                <template x-if="!isMulti && isSelected(id)">
                                    <div class="ml-auto flex items-center">
                                        @include('beartropy-ui-svg::beartropy-check', [
                                            'class' => 'shrink-0 text-gray-700 dark:text-gray-400 ' . ($sizePreset['iconSize'] ?? '')
                                        ])
                                    </div>
                                </template>
                                <template x-if="isMulti">
                                    <div class="ml-auto flex items-center">
                                        <input type="checkbox" :checked="isSelected(id)" class="form-checkbox pointer-events-none" @click.prevent />
                                    </div>
                                </template>
                            </button>
                        </li>
                    </template>
                    <template x-if="loading">
                        <li class="flex items-center justify-center gap-2 p-2 {{ $colorDropdown['loading_text'] ?? 'text-xs text-gray-500' }}">
                            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity=".25"/>
                                <path d="M21 12a9 9 0 0 1-9 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            {{ __('beartropy-ui::ui.loading') }}
                        </li>
                    </template>
                    <template x-if="!loading && filteredOptions().length === 0">
                        @if(isset($afterOptions))
                            <div class="{{$afterOptions->attributes->get('class') ?? 'p-2'}}">
                                {!! $afterOptions !!}
                            </div>
                        @else
                            <li class="{{ $isEmpty ? 'p-2 text-base text-gray-700 dark:text-gray-300' : $colorDropdown['loading_text'] ?? 'text-center text-xs text-gray-500 p-2' }}">{{ $isEmpty ? $emptyMessage : __('beartropy-ui::ui.no_results') }}</li>
                        @endif
                    </template>

                </ul>
            </x-beartropy-ui::base.dropdown-base>
            </div>
        </x-slot>
    </x-beartropy-ui::base.input-trigger-base>
    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
