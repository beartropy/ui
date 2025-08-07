@php
    [$colorPreset, $sizePreset] = $getComponentPresets('input');
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
            $refs.input.addEventListener('input', () => {
                value = $refs.input.value;
            });
            $nextTick(() => {
                value = $refs.input.value;
            });
        "
    class="flex items-center w-full group relative"
>
    <div
        class="flex items-center group w-full rounded transition-all shadow-sm
            {{ $colorPreset['bg'] ?? '' }}
            {{ $borderClass ?? '' }}
            {{ $ringClass ?? '' }}
            {{ $colorPreset['disabled_bg'] ?? '' }}
            {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}
            {{ $sizePreset['px'] ?? '' }}
            {{ $sizePreset['height'] ?? '' }}"
        @if($disabled) aria-disabled="true" @endif
    >
        {{-- Start slot --}}
        @if (trim($start ?? ''))
            <div class="flex items-center space-x-2 h-full pr-2 beartropy-inputbase-start-slot">
                {{ $start }}
            </div>
        @endif

        <div class="w-full h-full flex items center" wire:ignore>
            <input
                x-ref="input"
                x-bind:type="(typeof showPassword !== 'undefined' && showPassword) ? 'text' : '{{ $type }}'"
                class="flex-1 bg-transparent outline-none border-none shadow-none beartropy-input
                    {{ $sizePreset['font'] ?? '' }}
                    {{ $colorPreset['text'] ?? '' }}
                    {{ $colorPreset['placeholder'] ?? '' }}
                    {{ $colorPreset['disabled_text'] ?? '' }}"
                placeholder="{{ $placeholder ?? '' }}"
                {{ $attributes->merge([
                    'disabled' => $disabled,
                    'aria-invalid' => $hasError ? 'true' : 'false',
                ]) }}
            >
        </div>

        {{-- End slot --}}
        @if (trim($end ?? ''))
            <div class="flex items-center h-full space-x-2 pl-1 beartropy-inputbase-end-slot">
                {{ $end }}
            </div>
        @endif
    </div>

</div>
