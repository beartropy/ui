@php
    [$colorPreset, $sizePreset, $shouldFill] = $getComponentPresets('input');
    $disabled = $attributes->get('disabled');
    $inputId = $attributes->get('id') ?? 'input-' . uniqid();
    $borderClass = $hasError ? ($colorPreset['border_error'] ?? $colorPreset['border']) : $colorPreset['border'];
    $ringClass = $hasError ? ($colorPreset['ring_error'] ?? $colorPreset['ring']) : $colorPreset['ring'];

@endphp

<div
    x-data="{
        value: @js($value ?? ''),
        showPassword: false,
        copySuccess: false,
        setValue(value) {
            this.value = value;
            this.$refs.input.value = value;
            this.$refs.input.dispatchEvent(new Event('input'));
        },
        clear() {
            this.value = '';
            this.$refs.input.value = '';
            this.$refs.input.dispatchEvent(new Event('input'));
        },
        copyToClipboard() {
            navigator.clipboard.writeText(this.$refs.input.value).then(() => {
                this.copySuccess = true;
                setTimeout(() => this.copySuccess = false, 1000);
            });
        },
    }"
    x-init="
        value = $refs.input.value;
        $refs.input.addEventListener('input', () => { value = $refs.input.value; });
        $nextTick(() => { value = $refs.input.value; });
    "
    class="flex items-center w-full min-w-0 group relative"
>
    <div
        class="flex items-center group w-full min-w-0 overflow-hidden rounded-lg transition-all shadow-sm
            {{ $shouldFill ? $colorPreset['bg'] : 'bg-white dark:bg-gray-900' }}
            {{ $borderClass ?? '' }}
            {{ $ringClass ?? '' }}
            {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}
            {{ $sizePreset['height'] ?? '' }}"
        @if($disabled) aria-disabled="true" @endif
    >
        {{-- Start slot --}}
        @if (trim($start ?? ''))
            <div class="flex shrink-0 self-stretch beartropy-inputbase-start-slot">
                {{ $start }}
            </div>
        @endif

        <div class="flex-1 min-w-0 h-full flex items-center {{ $sizePreset['px'] ?? '' }}" wire:key="{{ $inputId }}">
            <input
                data-beartropy-input
                id="{{ $inputId }}"
                x-ref="input"
                x-bind:type="showPassword ? 'text' : '{{ $type }}'"
                class="w-full min-w-0 bg-transparent outline-none border-none shadow-none beartropy-input
                    {{ $sizePreset['font'] ?? '' }}
                    {{ $colorPreset['text'] ?? '' }}
                    {{ $colorPreset['placeholder'] ?? '' }}"
                placeholder="{{ $placeholder ?? '' }}"
                autocomplete="off"
                {{ $attributes->merge([
                    'disabled' => $disabled,
                    'aria-invalid' => $hasError ? 'true' : 'false',
                ]) }}
            >
        </div>

        {{-- End slot --}}
        @if (trim($end ?? ''))
            <div class="flex shrink-0 self-stretch beartropy-inputbase-end-slot">
                {{ $end }}
            </div>
        @endif

        {{-- Dropdown slot --}}
        @if (trim($dropdown ?? ''))
            {{ $dropdown }}
        @endif
    </div>
</div>
