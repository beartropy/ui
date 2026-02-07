@php
    [$colorPreset, $sizePreset] = $getComponentPresets('file-dropzone');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $labelClass = $hasError ? ($colorPreset['label_error'] ?? $colorPreset['label']) : $colorPreset['label'];
    $inputName = $multiple ? $name . '[]' : $name;

    $placeholderText = $placeholder
        ?? ($multiple ? __('beartropy-ui::ui.drop_files_here') : __('beartropy-ui::ui.drop_file_here'));

    $acceptHint = $getAcceptHint();
@endphp

<div
    x-data="beartropyFileDropzone({
        multiple: {{ $multiple ? 'true' : 'false' }},
        accept: {{ $accept ? Js::from($accept) : 'null' }},
        maxFileSize: {{ $maxFileSize ?? 'null' }},
        maxFiles: {{ $maxFiles ?? 'null' }},
        disabled: {{ $disabled ? 'true' : 'false' }},
        existingFiles: {{ Js::from($existingFiles) }},
        i18n: {
            file_too_large: {{ Js::from(__('beartropy-ui::ui.file_too_large')) }},
            file_type_not_accepted: {{ Js::from(__('beartropy-ui::ui.file_type_not_accepted')) }},
            max_files_exceeded: {{ Js::from(__('beartropy-ui::ui.max_files_exceeded')) }},
        },
    })"
    x-on:livewire-upload-start.window="
        const n = $event.detail?.property ?? '';
        @if($wireModelValue)
            if (n === '{{ $wireModelValue }}' || n.startsWith('{{ $wireModelValue }}.')) {
                uploading = true; progress = 0;
            }
        @endif
    "
    x-on:livewire-upload-finish.window="
        const n = $event.detail?.property ?? '';
        @if($wireModelValue)
            if (n === '{{ $wireModelValue }}' || n.startsWith('{{ $wireModelValue }}.')) {
                uploading = false; progress = 0;
                files.forEach(f => f.status = 'complete');
            }
        @endif
    "
    x-on:livewire-upload-error.window="
        const n = $event.detail?.property ?? '';
        @if($wireModelValue)
            if (n === '{{ $wireModelValue }}' || n.startsWith('{{ $wireModelValue }}.')) {
                uploading = false;
                files.forEach(f => f.status = 'error');
            }
        @endif
    "
    x-on:livewire-upload-progress.window="
        const n = $event.detail?.property ?? '';
        @if($wireModelValue)
            if (n === '{{ $wireModelValue }}' || n.startsWith('{{ $wireModelValue }}.')) {
                progress = $event.detail.progress;
            }
        @endif
    "
    class="flex flex-col gap-2 {{ $disabled ? $colorPreset['disabled'] : '' }}"
    {{ $attributes->whereDoesntStartWith(['wire:model', 'id', 'name', 'accept', 'multiple', 'disabled']) }}
>
    {{-- Label --}}
    @if($label)
        <label for="{{ $id }}" class="{{ $labelClass }}">{{ $label }}</label>
    @endif

    {{-- Hidden file input --}}
    <input
        type="file"
        x-ref="input"
        id="{{ $id }}"
        name="{{ $inputName }}"
        @if($multiple) multiple @endif
        @if($accept) accept="{{ $accept }}" @endif
        @if($disabled) disabled @endif
        class="sr-only"
        @change="addFiles($event)"
        {{ $attributes->whereStartsWith('wire:model') }}
    />

    {{-- Drop zone area --}}
    <div
        @click="openPicker()"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="dragging = false; addFiles($event)"
        @keydown.enter.prevent="openPicker()"
        @keydown.space.prevent="openPicker()"
        :class="{
            '{{ $colorPreset['dropzone_drag'] }}': dragging,
            '{{ $colorPreset['dropzone_error'] }}': errors.length,
        }"
        class="{{ $colorPreset['dropzone'] }} {{ $colorPreset['dropzone_hover'] }} min-h-[10rem] p-6"
        role="button"
        tabindex="0"
        aria-label="{{ $placeholderText }}"
    >
        {{-- Empty state --}}
        <div x-show="!files.length && !existingFiles.length" class="flex flex-col items-center gap-2">
            <x-beartropy-ui::icon name="arrow-up-tray" class="w-10 h-10 {{ $colorPreset['icon'] }}" />
            <span class="text-sm font-medium {{ $colorPreset['text'] }}">{{ $placeholderText }}</span>
            @if($acceptHint)
                <span class="text-xs {{ $colorPreset['subtext'] }}">{{ $acceptHint }}</span>
            @endif
        </div>

        {{-- File list --}}
        <div x-show="files.length || existingFiles.length" x-cloak class="w-full flex flex-col gap-2" @click.stop>
            {{-- Existing files --}}
            <template x-for="ef in existingFiles" :key="ef.id">
                <div class="{{ $colorPreset['file_item'] }} flex items-center gap-3 p-2 rounded-lg">
                    <template x-if="ef.url && ef.type && ef.type.startsWith('image/')">
                        <img :src="ef.url" class="w-10 h-10 rounded object-cover shrink-0" alt="" />
                    </template>
                    <template x-if="!ef.url || !ef.type || !ef.type.startsWith('image/')">
                        <span class="w-10 h-10 flex items-center justify-center rounded bg-gray-100 dark:bg-gray-700 shrink-0">
                            <x-beartropy-ui::icon name="document" class="w-5 h-5 {{ $colorPreset['file_size'] }}" />
                        </span>
                    </template>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium truncate {{ $colorPreset['file_name'] }}" x-text="ef.name"></div>
                        <div class="text-xs {{ $colorPreset['file_size'] }}" x-show="ef.size" x-text="ef.size ? formatSize(ef.size) : ''"></div>
                    </div>
                    @if($clearable)
                        <button
                            @click.stop="removeExisting(ef.id)"
                            type="button"
                            class="{{ $colorPreset['remove'] }} p-1"
                            :aria-label="'{{ __('beartropy-ui::ui.remove_file') }}'"
                        >
                            <x-beartropy-ui::icon name="x-mark" class="w-4 h-4" />
                        </button>
                    @endif
                </div>
            </template>

            {{-- New files --}}
            <template x-for="f in files" :key="f.id">
                <div class="{{ $colorPreset['file_item'] }} flex items-center gap-3 p-2 rounded-lg">
                    @if($preview)
                        <template x-if="f.preview">
                            <img :src="f.preview" class="w-10 h-10 rounded object-cover shrink-0" alt="" />
                        </template>
                    @endif
                    <template x-if="!f.preview">
                        <span class="w-10 h-10 flex items-center justify-center rounded bg-gray-100 dark:bg-gray-700 shrink-0">
                            <span x-show="f.file.type.startsWith('image/')"><x-beartropy-ui::icon name="photo" class="w-5 h-5 {{ $colorPreset['file_size'] }}" /></span>
                            <span x-show="f.file.type === 'application/pdf'"><x-beartropy-ui::icon name="document-text" class="w-5 h-5 {{ $colorPreset['file_size'] }}" /></span>
                            <span x-show="f.file.type.startsWith('video/')"><x-beartropy-ui::icon name="film" class="w-5 h-5 {{ $colorPreset['file_size'] }}" /></span>
                            <span x-show="f.file.type.startsWith('audio/')"><x-beartropy-ui::icon name="musical-note" class="w-5 h-5 {{ $colorPreset['file_size'] }}" /></span>
                            <span x-show="!f.file.type.startsWith('image/') && f.file.type !== 'application/pdf' && !f.file.type.startsWith('video/') && !f.file.type.startsWith('audio/')"><x-beartropy-ui::icon name="document" class="w-5 h-5 {{ $colorPreset['file_size'] }}" /></span>
                        </span>
                    </template>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium truncate {{ $colorPreset['file_name'] }}" x-text="f.file.name"></div>
                        <div class="text-xs {{ $colorPreset['file_size'] }}" x-text="formatSize(f.file.size)"></div>
                        {{-- Per-file progress bar --}}
                        <div x-show="uploading" class="mt-1 h-1 rounded-full overflow-hidden {{ $colorPreset['progress_track'] }}">
                            <div :style="`width: ${progress}%`" class="h-full transition-all {{ $colorPreset['progress'] }}"></div>
                        </div>
                    </div>
                    {{-- Status icons --}}
                    <span x-show="f.status === 'complete'" x-cloak class="text-emerald-500">
                        <x-beartropy-ui::icon name="check-circle" class="w-5 h-5" />
                    </span>
                    <span x-show="f.status === 'error'" x-cloak class="text-red-500">
                        <x-beartropy-ui::icon name="x-circle" class="w-5 h-5" />
                    </span>
                    {{-- Remove button --}}
                    @if($clearable)
                        <button
                            @click.stop="removeFile(f.id)"
                            type="button"
                            class="{{ $colorPreset['remove'] }} p-1"
                            :aria-label="'{{ __('beartropy-ui::ui.remove_file') }}'"
                        >
                            <x-beartropy-ui::icon name="x-mark" class="w-4 h-4" />
                        </button>
                    @endif
                </div>
            </template>

            {{-- Add more button --}}
            @if($multiple)
                <button
                    @click.stop="openPicker()"
                    type="button"
                    class="text-xs {{ $colorPreset['text'] }} hover:underline self-start mt-1"
                >
                    {{ __('beartropy-ui::ui.choose_file') }}
                </button>
            @endif
        </div>
    </div>

    {{-- Client-side validation errors --}}
    <div x-show="errors.length" x-cloak class="flex flex-col gap-1">
        <template x-for="err in errors" :key="err">
            <span class="text-xs text-red-500" x-text="err"></span>
        </template>
    </div>

    {{-- Clear all button --}}
    @if($clearable)
        <button
            x-show="files.length > 1"
            x-cloak
            @click="clearFiles()"
            type="button"
            class="text-xs {{ $colorPreset['clear_all'] }} self-start"
        >
            {{ __('beartropy-ui::ui.clear_all') }}
        </button>
    @endif

    {{-- Server errors + hint --}}
    <x-beartropy-ui::support.field-help
        :error-message="$finalError"
        :hint="$help ?? $hint ?? null"
    />
</div>
