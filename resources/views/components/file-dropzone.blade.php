@php
    [$colorPreset, $sizePreset] = $getComponentPresets('file-dropzone');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];


    $id = $attributes->get('id') ?? 'beartropy-filedropzone-'.uniqid();
    $name = $name ?? $id;
    $inputName = $multiple ? $name . '[]' : $name;
@endphp

<div
    x-data="{
        files: [],
        uploading: false,
        syncInput() {
            this.files = Array.from(this.$refs.input.files || []);
        },
        addFiles(e) {
            let newFiles = Array.from(e.target.files || e.dataTransfer.files);
            if ({{ $multiple ? 'true' : 'false' }}) {
                // Livewire: agrega todos
                const dt = new DataTransfer();
                this.files.concat(newFiles).forEach(f => dt.items.add(f));
                this.$refs.input.files = dt.files;
            } else {
                this.$refs.input.files = new DataTransfer().items.add(newFiles[0]).files;
            }
            this.syncInput();
        },
        clearFiles() {
            this.$refs.input.value = '';
            this.files = [];
        },
        removeFile(i) {
            const dt = new DataTransfer();
            this.files.forEach((f, idx) => {
                if (i !== idx) dt.items.add(f);
            });
            this.$refs.input.files = dt.files;
            this.syncInput();
        },
        init() {
            this.syncInput();
            // Escucha los eventos de Livewire para upload
            window.addEventListener('livewire-upload-start', () => { this.uploading = true; this.progress = 0; });
            window.addEventListener('livewire-upload-finish', () => { this.uploading = false; this.progress = 0; });
            window.addEventListener('livewire-upload-error', () => { this.uploading = false; });
            window.addEventListener('livewire-upload-progress', e => { this.progress = e.detail.progress; });
        }
    }"
    x-init="init"
    class="{{ $colorPreset['wrapper'] }} {{ $disabled ? $colorPreset['disabled'] : '' }}"
>
    @if($label)
        <label for="{{ $id }}" class="{{ $labelClass }}">
            {{ $label }}
        </label>
    @endif

    <div
        x-ref="dropzone"
        x-on:dragover.prevent
        x-on:drop.prevent="addFiles($event)"
        class="{{ $colorPreset['dropzone'] }}"
        tabindex="0"
    >
        @if($icon)
            <x-beartropy-ui::icon :name="$icon" class="{{ $colorPreset['icon'] }}" />
        @endif

        <!-- CAMBIO CLAVE: input hidden, y usamos label para click -->
        <input
            type="file"
            id="{{ $id }}"
            name="{{ $inputName }}"
            x-ref="input"
            @if($multiple) multiple @endif
            @if($accept) accept="{{ $accept }}" @endif
            class="hidden"
            @change="addFiles($event)"
            @if($disabled) disabled @endif
            {{ $attributes->whereDoesntStartWith('wire:') }}
            {{ $attributes->whereStartsWith('wire:model') }}
        />

        <label for="{{ $id }}" class="w-full h-full flex flex-col items-center justify-center cursor-pointer">
            <div class="{{ $colorPreset['filelist'] }}">
                <span x-show="!files.length" class="{{ $colorPreset['emptyText'] }}">
                    <div class="flex flex-col items-center">
                        @include('beartropy-ui-svg::beartropy-upload', ['class' => 'w-24 h-24', 'tabindex' => '-1'])
                        <span class="block text-xl">
                            {{__('Drop files here or click to select') }}
                        </span>
                    </div>
                </span>
                <span x-show="files.length" class="{{ $colorPreset['filename'] }}">
                    <span x-text="files.map(f => f.name).join(', ')"></span>
                </span>
            </div>
        </label>
    </div>

    @if($preview)
        <div class="flex gap-3 mt-2" x-show="files.length">
            <template x-for="(file, i) in files" :key="i">
                <div class="relative">
                    <img
                        :src="file.type.startsWith('image/') ? URL.createObjectURL(file) : ''"
                        x-show="file.type.startsWith('image/')"
                        class="{{ $colorPreset['preview'] }}"
                        @load="URL.revokeObjectURL($el.src)"
                    />
                    @if($hasWireModel)
                        <div x-show="uploading" class="mt-3 w-full h-2 rounded bg-beartropy-100 overflow-hidden transition-all" style="min-width: 40%">
                            <div :style="`width: ${progress}%;`"
                                class="h-full transition-all duration-300 bg-beartropy-500"></div>
                        </div>
                    @endif
                    @if($clearable)
                        <button type="button" class="flex w-full justify-end text-red-500"
                            @click="removeFile(i)">
                            &times;
                        </button>
                    @endif
                </div>
            </template>
        </div>
    @else
        @if($hasWireModel)
            <div x-show="uploading" class="w-full h-2 rounded bg-beartropy-100 overflow-hidden transition-all" style="min-width: 40%">
                <div :style="`width: ${progress}%;`"class="h-full transition-all duration-300 bg-beartropy-500"></div>
            </div>
        @endif
    @endif

    @if($clearable)
        <button type="button" x-show="files.length" @click="clearFiles"
            class="text-xs mt-1 text-red-500 hover:underline">{{__('Clear all')}}</button>
    @endif

    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
