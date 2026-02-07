@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('input');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $inputId = $attributes->get('id') ?? 'input-file-' . uniqid();
    $placeholder = $placeholder ?? __('beartropy-ui::ui.choose_file');
    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];

    $wrapperClass = $attributes->get('class') ?? '';
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
                    : `${this.files.length} {{ __('beartropy-ui::ui.files_selected') }}`;
                this.uploaded = false;
                this.validationErrors = false;
            }
        },

        clear(){
            this.files = [];
            this.label = @js($placeholder);
            this.uploaded = false;
            this.validationErrors = false;

            // Reset the input
            this.$refs.fileInput.value = '';
        },

        openPicker(){
            if(!@json($disabled)) {
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
    x-effect="
        if ({{ $hasError ? 'true' : 'false' }}) {
            validationErrors = true;
            uploading = false;
        }
    "

    class="flex flex-col w-full {{ $wrapperClass }}"
>
    @if($label)
        <label for="{{ $inputId }}-trigger" class="{{ $labelClass }}">{{ $label }}</label>
    @endif

    {{-- Hidden real file input --}}
    <input
        x-ref="fileInput"
        id="{{ $inputId }}"
        name="{{ $name ?? $inputId }}"
        type="file"
        {{ $multiple ? 'multiple' : '' }}
        @if($accept) accept="{{ $accept }}" @endif
        {{ $attributes->whereStartsWith('wire:model') }}
        class="sr-only"
        x-on:change="onChange($event)"
        @disabled($disabled)
    />

    {{-- Trigger base --}}
    <x-beartropy-ui::base.input-trigger-base
        id="{{ $inputId }}-trigger"
        color="{{ $presetNames['color'] }}"
        size="{{ $presetNames['size'] }}"
        :has-error="$hasError"
        :fill="$attributes->has('fill') || $shouldFill"
        :outline="$attributes->has('outline')"
        :disabled="$disabled"
        {{ $attributes->whereDoesntStartWith(['wire:model', 'id', 'name', 'accept', 'multiple', 'disabled', 'class', 'style']) }}
    >
        <x-slot name="start">
            <span class="flex items-center px-2 {{ $colorPreset['text'] ?? '' }}">
                <x-beartropy-ui::icon name="paper-clip" class="w-4 h-4 opacity-70 shrink-0" />
            </span>
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
                aria-label="{{ __('beartropy-ui::ui.loading') }}"
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
                    aria-label="{{ __('beartropy-ui::ui.validation_error') }}"
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
                    aria-label="{{ __('beartropy-ui::ui.uploaded') }}"
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
        :hint="$help ?? $hint"
    />
</div>
