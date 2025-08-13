@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('input');

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
        #$inputId = $attributes->wire('model')->value();
    } elseif ($isAlpineExternal) {
        $extraInputAttrs['x-model'] = $xModel;
    } elseif ($isAlpineLocal) {
        $extraInputAttrs['x-model'] = 'value';
        $extraInputAttrs['value'] = $value ?? '';
    }
    $extraInputAttrs['autocomplete'] = "off";

    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];

    $loadingTargetsOverride = null;
    if ($attributes->has('wire:target')) {
        $wireActionTargets = collect([$attributes->get('wire:target')]);
    } else {
        $wireActionTargets = collect($attributes->getAttributes())
            ->filter(fn ($v, $k) => Str::startsWith($k, 'wire:'))
            ->reject(fn ($v, $k) => Str::startsWith($k, 'wire:model'))
            // Nos quedamos con el "valor" del wire:*, que es el método/prop objetivo (string)
            ->map(function ($v) {
                // En Blade suele venir como string directamente
                if (is_string($v)) return $v;
                // fallback defensivo
                if (is_array($v)) return head($v);
                return null;
            })
            ->filter()
            ->unique()
            ->values();
    }


    // Si el usuario pasó $loadingTargets lo usamos; sino, usamos lo detectado
    $wireLoadingTargets = $loadingTargetsOverride
        ? collect(is_array($loadingTargetsOverride) ? $loadingTargetsOverride : explode(',', (string) $loadingTargetsOverride))
            ->map(fn($s) => trim($s))
            ->filter()
            ->unique()
            ->values()
        : $wireActionTargets;

    // String CSV para wire:target (Livewire soporta targets separados por coma)
    $wireLoadingTargetsCsv = $wireLoadingTargets->implode(',');
@endphp

<div
    class="flex flex-col w-full relative"
    {{-- Livewire actualizará este atributo cuando :options cambie --}}
    data-options='@json($options ?? [])'

    x-data="{
        // --- estado ---
        open: false,
        highlighted: -1,
        options: [],
        filtered: [],

        // --- config desde Blade ---
        inputId: '{{ $inputId }}',
        isLivewire: {{ $isLivewire ? 'true' : 'false' }},
        labelKey: @js($attributes->get('option-label', 'name')),
        valueKey: @js($attributes->get('option-value', 'id')),

        // --- lifecycle ---
        init() {
            // 1) sync inicial
            this._syncOptionsFromAttr();

            // 2) observar cambios del atributo data-options (Livewire morphdom)
            const obs = new MutationObserver(muts => {
                for (const m of muts) {
                    if (m.type === 'attributes' && m.attributeName === 'data-options') {
                        this._syncOptionsFromAttr();
                    }
                }
            });
            obs.observe(this.$el, { attributes: true });
            this._obs = obs;
        },

        // --- helpers de datos ---
        _syncOptionsFromAttr() {
            const raw = this.$el.getAttribute('data-options') || '[]';
            try {
                const parsed = JSON.parse(raw);
                this.options = Array.isArray(parsed) ? parsed : [];
            } catch (_) {
                this.options = [];
            }
            this.filtered = this.options;
            if (this.highlighted >= this.filtered.length) {
                this.highlighted = this.filtered.length ? 0 : -1;
            }
            this._reconcileFromVisible();
        },
        normalize(s) {
            if (!s) return '';
            return s.toString()
                .normalize('NFD').replace(/\p{Diacritic}/gu, '')
                .trim().toLowerCase();
        },
        getLabel(o) { return o?.[this.labelKey] ?? ''; },
        getValue(o) { return o?.[this.valueKey] ?? ''; },
        exactMatch(txt) {
            const t = this.normalize(txt);
            return this.options.find(o => this.normalize(this.getLabel(o)) === t) || null;
        },

        // --- eventos / lógica principal ---
        onInput(e) {
            const raw = (e?.target?.value ?? '');
            const t = this.normalize(raw);

            this.filtered = !t
                ? this.options
                : this.options.filter(o => this.normalize(this.getLabel(o)).includes(t));

            this.open = true;
            this.highlighted = this.filtered.length ? 0 : -1;

            // match exacto => id, sino => texto libre
            this._setHiddenFromRawOrMatch(raw);
        },
        move(delta) {
            if (!this.open || !this.filtered.length) return;
            const n = this.filtered.length;
            this.highlighted = (this.highlighted + delta + n) % n;
        },
        choose(idx) {
            const opt = this.filtered[idx];
            if (!opt) return;
            this.setVisibleValue(this.getLabel(opt));
            if (this.isLivewire && this.$refs.livewireValue) {
                this.$refs.livewireValue.value = this.getValue(opt);
                this.$refs.livewireValue.dispatchEvent(new Event('input', { bubbles: true }));
            }
            this.open = false;
        },
        confirm() {
            if (this.highlighted >= 0) {
                this.choose(this.highlighted);
            } else {
                const raw = this.$refs.input?.value ?? '';
                this._setHiddenFromRawOrMatch(raw);
                this.open = false;
            }
        },
        close() { this.open = false; },

        setVisibleValue(v) {
            const el = document.getElementById(this.inputId);
            if (!el) return;
            el.value = v ?? '';
            el.dispatchEvent(new Event('input', { bubbles: true }));
        },
        clearBoth() {
            this.setVisibleValue('');
            if (this.isLivewire && this.$refs.livewireValue) {
                this.$refs.livewireValue.value = '';
                this.$refs.livewireValue.dispatchEvent(new Event('input', { bubbles: true }));
            }
            this.filtered = this.options;
            this.highlighted = -1;
            this.open = false;
        },
        _setHiddenFromRawOrMatch(raw) {
            if (!this.isLivewire || !this.$refs.livewireValue) return;
            const match = this.exactMatch(raw);
            this.$refs.livewireValue.value = match ? this.getValue(match) : raw;
            this.$refs.livewireValue.dispatchEvent(new Event('input', { bubbles: true }));
        },
        _reconcileFromVisible() {
            const el = document.getElementById(this.inputId);
            if (!el) return;
            this._setHiddenFromRawOrMatch(el.value || '');
        },
    }"
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
        x-ref="input"
        x-on:input="onInput($event)"
        id="{{ $inputId }}"
        type="{{ $type }}"
        size="{{ $size }}"
        color="{{ $color }}"
        placeholder="{{ $placeholder }}"
        custom-error="{{ $customError }}"
        hint="{{ $hint }}"
        has-error="{{ $hasError }}"
        @click="open = true"
        {{ $attributes->whereDoesntStartWith('wire:model')->merge($extraInputAttrs) }}
        x-on:input="onInput"
        x-on:keydown.down.prevent="move(1)"
        x-on:keydown.up.prevent="move(-1)"
        x-on:keydown.enter.prevent="confirm()"
        x-on:keydown.tab="confirm()"
        x-on:keydown.escape.prevent="close()"
        x-on:blur="confirm()"
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

        @if(isset($end) || $clearable || $copyButton || ($type === 'password' && $togglePassword) || $iconEnd || !empty($wireLoadingTargetsCsv))
            <x-slot name="end">
            @if(!empty($wireLoadingTargetsCsv))
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
                    x-on:click="clearBoth()"
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
        <x-slot name="dropdown">
            <x-beartropy-ui::base.dropdown-base
                placement="left"
                side="bottom"
                color="{{ $presetNames['color'] }}"
                preset-for="select"
                width="w-full"
                x-show="open"
                @click.outside="close()" {{-- mejor que .away --}}
            >
                <template x-if="filtered.length">
                    <ul class="max-h-60 overflow-auto beartropy-thin-scrollbar" role="listbox">
                        <template x-for="(opt, idx) in filtered" :key="idx">
                            <li
                                role="option"
                                class="px-3 py-2 cursor-pointer select-none text-gray-700 dark:text-gray-300 text-sm"
                                :class="idx === highlighted ? 'bg-neutral-100 dark:bg-neutral-800' : ''"
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
