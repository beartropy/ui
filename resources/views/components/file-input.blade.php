@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('input');
    [$colorDropdown, $sizeDropdown] = $getComponentPresets('select');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $isDisabled = $attributes->has('disabled');

    $inputId = $attributes->get('id') ?? 'input-file-' . uniqid();

    $wrapperClass = $attributes->get('class') ?? '';

    $label = $label ?? null;
    $placeholder = $placeholder ?? 'Elegir archivo...';
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];

    $loadingTargetsOverride = null;
    if($attributes->get('wire:target')) {
        $wireLoadingTargetsCsv = $attributes->get('wire:target');
    } else {
        $wireActionTargets = collect($attributes->getAttributes())
            ->filter(fn ($v, $k) => Str::startsWith($k, 'wire:'))
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

@endphp

<div
    x-data="{
        files: [],
        label: @js($placeholder),
        uploading: false,
        uploaded: false,
        validationErrors: false,

        onChange(e){
            const selectedFiles = Array.from(e.target.files || []);

            if (selectedFiles.length > 0) {
                this.files = selectedFiles;
                this.label = this.files.length === 1
                    ? this.files[0].name
                    : `${this.files.length} archivos seleccionados`;
                this.uploaded = false;
                this.validationErrors = false;
            }
        },

        clear(){
            this.files = [];
            this.label = @js($placeholder);
            this.uploaded = false;
            this.validationErrors = false;

            // Resetear el input
            this.$refs.fileInput.value = '';
        },

        openPicker(){
            if(!@json($isDisabled)) {
                this.$refs.fileInput.click();
            }
        },
    }"
    x-on:livewire-upload-start.window="
        const n = $event.detail?.property ?? '';
        @if($wireModelValue)
            if (n === '{{ $wireModelValue }}' || n.startsWith('{{ $wireModelValue }}.')) {
                uploading = true; uploaded = false; validationErrors = false;
            }
        @endif
    "
    x-on:livewire-upload-finish.window="
        const n = $event.detail?.property ?? '';
        @if($wireModelValue)
            if (n === '{{ $wireModelValue }}' || n.startsWith('{{ $wireModelValue }}.')) {
                uploading = false; uploaded = true; validationErrors = false;
            }
        @endif
    "
    x-on:livewire-upload-error.window="
        const n = $event.detail?.property ?? '';
        @if($wireModelValue)
            if (n === '{{ $wireModelValue }}' || n.startsWith('{{ $wireModelValue }}.')) {
                uploading = false; uploaded = false; validationErrors = true;
            }
        @endif
    "
    x-effect="validationErrors = {{ $hasError ? 'true' : 'false' }}"

    class="flex flex-col w-full {{ $wrapperClass }}"
>
    @if($label)
        <label for="{{ $inputId }}-trigger" class="{{ $labelClass }}">{{ $label }}</label>
    @endif

    {{-- input file real (oculto) - CAMBIÉ EL REF AQUÍ --}}
    <input
        x-ref="fileInput" {{-- 👈 CAMBIÉ DE 'input' A 'fileInput' --}}
        id="{{ $inputId }}"
        name="{{ $name ?? $inputId }}"
        type="file"
        {{ $multiple ? 'multiple' : '' }}
        @if($accept) accept="{{ $accept }}" @endif
        {{ $attributes->whereStartsWith('wire:model') }}
        class="sr-only"
        x-on:change="onChange($event)"
        @disabled($isDisabled)
    />

    {{-- TRIGGER BASE --}}
    <x-beartropy-ui::base.input-trigger-base
        id="{{ $inputId }}-trigger"
        color="{{ $presetNames['color'] }}"
        size="{{ $presetNames['size'] }}"
        custom-error="{{ $customError }}"
        hint="{{ $hint }}"
        has-error="{{ $hasError }}"
        :fill="$attributes->has('fill') || $shouldFill"
        :outline="$attributes->has('outline')"
        {{ $attributes->whereDoesntStartWith(['wire:model', 'id', 'name', 'accept', 'multiple']) }}
    >
        <x-slot name="start">
            <x-beartropy-ui::icon name="paper-clip" class="w-4 h-4 opacity-70 shrink-0 text-gray-700 dark:text-gray-300" />
        </x-slot>

        <x-slot name="button">
            <div
                class="w-full min-w-0 inline-flex items-center justify-between gap-2 cursor-pointer select-none"
                @click="openPicker()"
                @keydown.enter.prevent="openPicker()"
                @keydown.space.prevent="openPicker()"
                role="button"
                tabindex="0"
            >
                <span class="truncate {{$colorPreset['text']}}" x-text="label"></span>

                <span class="shrink-0 inline-flex items-center gap-2">
                    @if($clearable)
                        <button
                            type="button"
                            @click.stop="clear()"
                            x-show="files.length"
                        >
                            @include('beartropy-ui-svg::beartropy-x-mark', [
                                'class' => 'shrink-0 text-gray-700 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 ' . ($sizePreset['iconSize'] ?? '')
                            ])
                        </button>
                    @endif

                    <span>
                        <x-beartropy-ui::icon name="arrow-up-tray" class="w-4 h-4 opacity-70 shrink-0 text-gray-700 dark:text-gray-300" />
                    </span>
                </span>
            </div>
        </x-slot>

        <x-slot name="end">
            <span
                x-show="uploading"
                aria-label="Cargando…"
                class="inline-flex items-center"
                x-cloak
            >
                @include('beartropy-ui-svg::beartropy-spinner', [
                    'class' => 'animate-spin shrink-0 text-gray-500 dark:text-gray-400 ' . ($sizePreset['iconSize'] ?? '')
                ])
            </span>

            @if ($hasError)
                <span
                    x-show="!uploading"
                    aria-label="Error de validación"
                    class="inline-flex items-center"
                    x-cloak
                >
                    @include('beartropy-ui-svg::beartropy-x-mark', [
                        'class' => 'shrink-0 text-red-600 dark:text-red-400 ' . ($sizePreset['iconSize'] ?? '')
                    ])
                </span>
            @else
                <span
                    x-show="!uploading && uploaded"
                    aria-label="Subido"
                    class="inline-flex items-center"
                    x-cloak
                >
                    @include('beartropy-ui-svg::beartropy-check', [
                        'class' => 'shrink-0 text-emerald-600 dark:text-emerald-400 ' . ($sizePreset['iconSize'] ?? '')
                    ])
                </span>
            @endif

            @isset($end)
                {!! $end !!}
            @endisset
        </x-slot>
    </x-beartropy-ui::base.input-trigger-base>
    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
