@php
    [$colorPreset, $sizePreset] = $getComponentPresets('chat-input');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $id = $id ?? ($name ?? uniqid('chat-input-'));
    $name = $name ?? $id;

    $wrapperClass = $hasError ? $colorPreset['wrapper_error'] ?? $colorPreset['wrapper'] : $colorPreset['wrapper'];

    if ($border) {
        $borderClass = $colorPreset['border'] ?? 'border border-gray-200 dark:border-gray-700/50 shadow-sm focus-within:ring-2 focus-within:ring-gray-200 dark:focus-within:ring-gray-700';
        $wrapperClass .= ' ' . $borderClass;
    }
@endphp

<div class="{{ isset($footer) ? 'mb-4' : '' }}">
    @if ($label)
        <label for="{{ $id }}" class="{{ $hasError ? $colorPreset['label_error'] : $colorPreset['label'] }}">
            {{ $label }} @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $wrapperClass }} {{ $colorPreset['main'] ?? '' }}"
        x-cloak
        x-data='{
        val: @if ($hasWireModel) $wire.entangle("{{ $wireModelValue }}") @else $el.querySelector("textarea").value @endif,
        isSingleLine: @json(!$stacked),
        stacked: @json($stacked),
        action: @json($action),
        submitOnEnter: @json($submitOnEnter),
        baseHeight: 0,
        checkLineTimeout: null,
        init() {
            this.$nextTick(() => {
                if (!this.stacked) {
                    this.baseHeight = $refs.textarea.clientHeight;
                }
                this.resize();
            });
            this.$watch("val", () => {
                this.$nextTick(() => this.resize());
            });
        },
        resize() {
            const textarea = $refs.textarea;
            const currentWidth = textarea.offsetWidth;
            textarea.style.width = currentWidth + "px";
            textarea.style.height = "auto";
            const scrollH = textarea.scrollHeight;
            const newHeight = Math.max(scrollH, this.baseHeight || 0);
            textarea.style.height = newHeight + "px";
            textarea.style.width = "";
            textarea.style.overflowY = newHeight >= 240 ? "auto" : "hidden";
            if (!this.stacked) {
                this.debouncedCheckLine(scrollH);
            }
        },
        debouncedCheckLine(scrollH) {
            clearTimeout(this.checkLineTimeout);
            this.checkLineTimeout = setTimeout(() => {
                if (!this.val || this.val.length === 0) {
                    this.isSingleLine = true;
                    return;
                }
                if (this.baseHeight > 0) {
                    // Hysteresis: different thresholds to prevent oscillation
                    if (this.isSingleLine) {
                        // Currently single-line: go multi if clearly overflows
                        if (scrollH > this.baseHeight + 5) {
                            this.isSingleLine = false;
                        }
                    } else {
                        // Currently multi-line: only go single if clearly fits with margin
                        if (scrollH <= this.baseHeight - 5) {
                            this.isSingleLine = true;
                        }
                    }
                }
            }, 150);
        },
        handleEnter(e) {
            if (this.submitOnEnter && this.action && !e.shiftKey) {
                e.preventDefault();
                $wire.call(this.action);
            }
        }
    }'
        :class="isSingleLine && !stacked ? 'grid grid-cols-[auto_1fr_auto] items-center gap-x-2' :
            'grid grid-cols-2 gap-y-2 items-center'">

        @if (isset($tools))
            <div class="text-gray-400"
                :class="isSingleLine && !stacked ? 'col-start-1 pl-2' :
                    'col-start-1 row-start-2 justify-self-start pl-2 pb-2'">
                {{ $tools }}
            </div>
        @endif

        <textarea id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}" rows="1"
            @if ($disabled) disabled @endif @if ($readonly) readonly @endif
            @if ($required) required @endif
            @if ($maxLength) maxlength="{{ $maxLength }}" @endif x-ref="textarea" x-model="val"
            class="{{ $colorPreset['input'] }} py-2 min-w-0 max-h-60 overflow-y-hidden beartropy-textarea beartropy-thin-scrollbar"
            style="field-sizing: content;"
            :class="isSingleLine && !stacked ? 'col-start-2' : 'col-span-2'" x-init="init()" x-on:input="resize()"
            x-on:keydown.enter="handleEnter($event)"
            {{ $attributes->whereDoesntStartWith('wire:model')->whereDoesntStartWith('wire:click')->whereDoesntStartWith('wire:keydown') }}
            @if ($hasWireModel) wire:model="{{ $wireModelValue }}" @endif>{{ old($name, $slot) }}</textarea>

        @if (isset($footer) || isset($actions))
            <div {{ ($actions ?? $footer)->attributes->merge(['class' => $colorPreset['footer']]) }}
                :class="isSingleLine && !stacked ? 'col-start-3 !p-0 !pr-2 py-2' :
                    'col-start-2 row-start-2 justify-self-end !p-0 !pr-2 !pb-2'">
                {{ $actions ?? $footer }}
            </div>
        @endif
    </div>

    <x-beartropy-ui::support.field-help :error-message="$finalError" :hint="$help ?? ($hint ?? null)" />
</div>
