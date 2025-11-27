@php
    [$colorPreset, $sizePreset] = $getComponentPresets('card', null);

    $hasWireTarget = $attributes->whereStartsWith('wire:target')->isNotEmpty();

    $wrapperClasses = $colorPreset['wrapper']
        . ($noBorder ? ' border-0 shadow-none' : ' border border-gray-200 dark:border-gray-700')
        . ($hasWireTarget ? ' relative' : '');

    $initialOpen = $defaultOpen ? 'true' : 'false';
@endphp

<div
    @if($collapsable)
        x-data="{ open: {{ $initialOpen }} }"
    @else
        x-data="{}"
    @endif
    {{ $attributes->merge(['class' => $wrapperClasses]) }}
>

    @if (!empty($title))
        <div
            class="{{ $colorPreset['title'] }} py-1 {{ $collapsable ? 'cursor-pointer select-none flex items-center justify-between gap-2' : 'border-b border-gray-200 dark:border-gray-700' }}"
            @if($collapsable)
                @click="open = !open"
                :class="open ? 'border-b border-gray-200 dark:border-gray-700' : 'border-b-0'"
            @endif
        >
            <div class="flex-1">
                {!! $title !!}
            </div>

            @if($collapsable)
                <span
                    class="inline-flex shrink-0 transition-transform duration-150"
                    :class="{ 'rotate-180': open }"
                >
                    <svg viewBox="0 0 20 20" class="w-6 h-6" fill="none">
                        <path
                            d="M5.25 7.75L10 12.25L14.75 7.75"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </span>
            @endif
        </div>
    @endif

    {{-- CONTENIDO PRINCIPAL --}}
    <div
        class="{{ $colorPreset['slot'] }} py-2 "
        @if($collapsable)
            x-cloak
            x-show="open"
            x-transition.opacity.duration.120ms
            x-collapse.duration.120ms
        @endif
    >
        {!! $slot !!}
    </div>

    {{-- FOOTER --}}
    @if (!empty($footer))
        <div
            class="border-t border-gray-200 dark:border-gray-700 pt-1 overflow-hidden"
            @if($collapsable)
                x-cloak
                x-show="open"
                x-transition.opacity.duration.120ms
                x-collapse.duration.120ms
            @endif
        >
            <div class="{{ $colorPreset['footer'] }}">
                {!! $footer !!}
            </div>
        </div>
    @endif

    @if($hasWireTarget)
        <div
            wire:loading.flex
            wire:target="{{ $attributes->wire('target')->value() }}"
            class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 rounded-xl items-center justify-center z-10"
        >
            <x-beartropy-ui::svg.beartropy-spinner class="w-10 h-10 text-primary-600" />
        </div>
    @endif
</div>
