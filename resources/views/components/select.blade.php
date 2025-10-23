@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('input');
    [$colorDropdown, $sizeDropdown] = $getComponentPresets('select');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $selectId = $attributes->get('id') ?? 'select-' . uniqid();
    $options = $options ?? [];
    $label = $label ?? null;
    $placeholder = $placeholder ?? 'Seleccionar...';
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];

    $isMulti = ($multiple ?? false);
    $name = $wireModelValue ?: ($name ?? $selectId);
    $parsedInitial = $isMulti
        ? (is_array($initialValue) ? $initialValue : (empty($initialValue) ? [] : [$initialValue]))
        : ($hasWireModel ? '' : ($initialValue ?? ''));

    $remoteUrl = $remoteUrl ?? null;
    $perPage = $perPage ?? 15;

    $optionsKey = md5(json_encode($options));

    $wrapperClass = $attributes->get('class') ?? '';
@endphp
<div
    x-data="{
        @if($hasWireModel)
            value: $wire.get('{{ $name }}'),
        @else
            value: {{ $isMulti ? (json_encode($parsedInitial)) : ("'$parsedInitial'") }},
        @endif
        open: false,
        toggle() {
        this.open = !this.open;
        if (this.open) {
            this.focusSearch();
            if (this.remoteUrl && !this.initDone) {
            this.page = 1;
            this.fetchOptions(true);
            this.initDone = true;
            }
        }
        },
        close() { this.open = false; },
        options: @js($options),
        search: '',
        isMulti: {{ $isMulti ? 'true' : 'false' }},
        maxChips: 3,
        perPage: {{ $perPage }},
        page: 1,
        hasMore: false,
        loading: false,
        remoteUrl: '{{ $remoteUrl }}',
        initDone: false,

        filteredOptions() {
            // Si remote: las opciones ya se filtran en backend
            if (this.remoteUrl) {
                return Object.entries(this.options);
            }
            const entries = Object.entries(this.options);
            if (!this.search) return entries;
            return entries.filter(([id, opt]) =>
                (opt.label ?? opt ?? id).toLowerCase().includes(this.search.toLowerCase())
            );
        },
        isSelected(id) {
            if (this.isMulti) {
                return Array.isArray(this.value)
                    ? this.value.map(String).includes(String(id))
                    : false;
            }
            return String(this.value) === String(id);
        },
        setValue(id) {
            id = String(id);
            if (this.isMulti) {
                if (!Array.isArray(this.value)) this.value = [];
                const valueStr = this.value.map(String);
                if (valueStr.includes(id)) {
                    this.value = valueStr.filter(v => v !== id);
                } else {
                    this.value = valueStr.concat([id]);
                }
                this.syncInput();
            } else {
                this.value = id;
                this.syncInput();
                this.close();
            }
        },
        removeSelected(id) {
            if (!this.value) return;
            this.value = this.value.filter(v => v !== id);
            this.syncInput();
        },
        syncInput() {
            @if(!$hasWireModel)
                this.$refs.multiInputs.innerHTML = '';
                if (this.isMulti) {
                    if (Array.isArray(this.value)) {
                        this.value.forEach(val => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = '{{ $name }}[]';
                            input.value = val;
                            this.$refs.multiInputs.appendChild(input);
                        });
                    }
                } else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '{{ $name }}';
                    input.value = this.value ?? '';
                    this.$refs.multiInputs.appendChild(input);
                }
            @endif

            @if($hasWireModel)
                $wire.set('{{ $name }}', this.value);
            @endif
        },
        visibleChips() {
            return Array.isArray(this.value) ? this.value.slice(0, this.maxChips) : [];
        },
        hiddenCount() {
            return Array.isArray(this.value) && this.value.length > this.maxChips
                ? this.value.length - this.maxChips
                : 0;
        },
        clearValue() {
            if (this.isMulti) {
                this.value = [];
            } else {
                this.value = '';
            }
            this.syncInput();
            // Si querés que se cierre el dropdown al limpiar:
            // this.close();
        },
        // === Remote/Lazy ===
        fetchOptions(reset = false) {
            if (!this.remoteUrl || this.loading) return;
            this.loading = true;
            let params = new URLSearchParams({
                q: this.search,
                page: this.page,
                per_page: this.perPage,
            }).toString();

            fetch(`${this.remoteUrl}?${params}`)
                .then(res => res.json())
                .then(data => {
                    if (reset) {
                        this.options = data.options || {};
                    } else {
                        this.options = Object.assign({}, this.options, data.options || {});
                    }
                    this.hasMore = data.hasMore;
                    this.loading = false;
                });
        },
        focusSearch() {
        this.$nextTick(() => {
            requestAnimationFrame(() => {
            // 1) Directo por x-ref, si llegó al input real
            let el = this.$refs.searchInput;

            // 2) Dentro del host del buscador
            if (!el && this.$refs.searchHost) {
                el = this.$refs.searchHost.querySelector('[data-beartropy-input]');
            }

            // 3) Como fallback, pero SIEMPRE scoped al componente actual
            if (!el) {
                el = this.$root.querySelector('[data-beartropy-input]');
            }

            if (el) {
                el.focus({ preventScroll: true });
                try { el.select?.(); } catch (_) {}
            }
            });
        });
        }

    }"
    x-init="
        @if($hasWireModel)
            if (isMulti) { $watch('$wire.{{ $name }}', v => { value = v; syncInput(); }); } else { $watch('$wire.{{ $name }}', v => value = v); }
        @endif
        if (isMulti && Array.isArray(value)) {
            value = value.map(String);
        }
        if (remoteUrl) {
            fetchOptions(true);
            initDone = true;
        }
        // Watch search changes
        $watch('search', value => {
            page = 1;
            fetchOptions(true);
        });
        $watch('open', (v) => { if (v) focusSearch(); });
    "
    class="flex flex-col w-full {{ $wrapperClass }}"
    wire:key="{{ $optionsKey }}"
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
        :fill="$attributes->has('fill')"
        :outline="$attributes->has('outline')"
    >
        @isset($start)
            <x-slot name="start">{!! $start !!}</x-slot>
        @endisset

        <x-slot name="button">
            <div @click="toggle()" class="flex flex-wrap items-center gap-1 min-h-[1.6em] cursor-pointer w-full {{ $colorDropdown['option_text'] ?? '' }}">
                {{-- MULTI SELECT: chips truncados --}}
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
                            <!-- Icono, solo si no hay avatar -->
                            <template x-if="!options[id]?.avatar && options[id]?.icon">
                                <span class="inline-flex w-5 h-5 justify-center items-center">
                                    <span x-text="options[id].icon" class="text-base"></span>
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
                    x-show="isMulti && value && hiddenCount()"
                    class="inline-flex items-center rounded px-2 py-0.5 text-xs font-semibold beartropy-select-badge {{ $colorDropdown['badge_bg'] ?? '' }} {{ $colorDropdown['badge_text'] ?? '' }}"
                >
                    +<span x-text="hiddenCount()"></span>
                </span>

                {{-- Placeholder --}}
                <span
                    x-show="!((isMulti && value && value.length) || (!isMulti && value))"
                    class="beartropy-placeholder"
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
                    <!-- Icono, solo si no hay avatar -->
                    <template x-if="!options[value]?.avatar && options[value]?.icon">
                        <span class="inline-flex w-5 h-5 justify-center items-center">
                            <span x-text="options[value].icon" class="text-base"></span>
                        </span>
                    </template>
                    <!-- Label -->
                    <span x-text="options[value]?.label ?? options[value] ?? value"></span>
                </span>
            </div>
        </x-slot>

        <x-slot name="end">
            @if($clearable)
                <span
                    x-show="(isMulti && value && value.length) || (!isMulti && value)"
                    @click.stop="clearValue()"
                    class="mr-1 cursor-pointer text-neutral-400 hover:text-red-500 transition"
                    title="Limpiar selección"
                >
                    @include('beartropy-ui-svg::beartropy-x-mark', [
                        'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 ' . ($sizePreset['iconSize'] ?? '')
                    ])
                </span>
            @endif
            <span @click="toggle()" class="cursor-pointer w-full">
                <svg class="w-5 h-5 pl-1 transition-transform duration-200 {{ $colorDropdown['option_icon'] ?? '' }}"
                    :class="{ 'rotate-180': open }"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
        </x-slot>


        <x-slot name="dropdown">
            <x-beartropy-ui::base.dropdown-base
                placement="left"
                side="bottom"
                color="{{$presetNames['color']}}"
                preset-for="select"
                width="w-full"
                x-show="open"
                @click.away="close()"
            >
                @if($searchable)
                    {{-- Search input --}}
                    <div class="p-2" x-ref="searchHost">
                        <x-beartropy-ui::input
                            type="text"
                            placeholder="Buscar..."
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
                    class="max-h-60 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800 beartropy-thin-scrollbar"
                    @scroll="if($event.target.scrollTop + $event.target.clientHeight >= $event.target.scrollHeight - 10 && hasMore && !loading) { page++; fetchOptions(); }"
                >
                    <template
                        x-for="[id, option] in filteredOptions()"
                        :key="id"
                    >
                        <li>
                            <button
                                type="button"
                                @click="setValue(id)"
                                class="w-full text-left px-4 py-2 flex items-center gap-2 {{ $colorDropdown['option_text'] ?? '' }} {{ $colorDropdown['option_hover'] ?? '' }}"
                                :class="isSelected(id) ? '{{ $colorDropdown['option_active'] ?? '' }} {{ $colorDropdown['option_selected'] ?? '' }}' : ''"
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
                                        <!-- Icono, solo si no hay avatar -->
                                        <template x-if="!option.avatar && option.icon">
                                            <span class="inline-flex w-6 h-6 justify-center items-center">
                                                <span x-text="option.icon" class="text-lg"></span>
                                            </span>
                                        </template>
                                        <!-- Label -->
                                        <span class="truncate font-medium" x-text="option.label ?? option ?? id"></span>
                                    </div>
                                    <!-- Descripción -->
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
                        <li class="{{ $colorDropdown['loading_text'] ?? 'text-center text-xs text-gray-500 p-2' }}">Cargando...</li>
                    </template>
                    <template x-if="!loading && filteredOptions().length === 0">
                        <li class="{{ $colorDropdown['loading_text'] ?? 'text-center text-xs text-gray-500 p-2' }}">No hay resultados.</li>
                    </template>

                </ul>
            </x-beartropy-ui::base.dropdown-base>
        </x-slot>
    </x-beartropy-ui::base.input-trigger-base>
    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
