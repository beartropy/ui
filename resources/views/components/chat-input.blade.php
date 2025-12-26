@php
    [$colorPreset, $sizePreset] = $getComponentPresets('chat-input');
    [$hasError, $finalError] = $getErrorState($attributes, $errors ?? null, $customError ?? null);
    [$hasWireModel, $wireModelValue] = $getWireModelState();

    $id = $id ?? ($name ?? uniqid('chat-input-'));
    $name = $name ?? $id;

    $wrapperClass = $hasError ? $colorPreset['wrapper_error'] ?? $colorPreset['wrapper'] : $colorPreset['wrapper'];
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
        x-data='{
        val: @if ($hasWireModel) $wire.entangle("{{ $wireModelValue }}") @else $el.querySelector("textarea").value @endif,
        isSingleLine: @json(!$stacked),
        stacked: @json($stacked),
        action: @json($action),
        submitOnEnter: @json($submitOnEnter),
        baseHeight: 0,
        init() {
            this.$nextTick(() => {
                if (!this.stacked) {
                    this.baseHeight = $refs.textarea.clientHeight;
                }
                this.resize();
                if (!this.stacked) {
                    this.checkLine();
                }
            });
            this.$watch("val",
        ()=> {
        this.$nextTick(() => this.resize());
        });
        },
        resize() {
        $refs.textarea.style.height = "auto";
        $refs.textarea.style.height = $refs.textarea.scrollHeight + "px";
        if (!this.stacked) {
        this.checkLine();
        }
        },
        checkLine() {
            if (!this.val) {
                this.isSingleLine = true;
                return;
            }
            if (this.baseHeight > 0) {
                this.isSingleLine = $refs.textarea.scrollHeight <= (this.baseHeight + 10);
            }
        }, handleEnter(e) { if
            (this.submitOnEnter && this.action && !e.shiftKey) { e.preventDefault(); $wire.call(this.action); } } }'
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
            class="{{ $colorPreset['input'] }} py-2 min-w-0 max-h-60 overflow-y-auto beartropy-textarea beartropy-thin-scrollbar"
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
