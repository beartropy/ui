@php
    [$colorPreset, $sizePreset] = $getComponentPresets('card', null);

    $hasWireTarget = $attributes->whereStartsWith('wire:target')->isNotEmpty();

    $wrapperClasses = $colorPreset['wrapper']
        . ($noBorder ? ' border-0 shadow-none' : ' border border-gray-200 dark:border-gray-700')
        . ($hasWireTarget ? ' relative' : ' relative'); // lo dejo siempre relative, no molesta
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
        class="{{ $colorPreset['slot'] }} py-2"
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
            class="absolute inset-0 z-20 flex items-center justify-center rounded-xl pointer-events-none"
        >
            <div class="absolute inset-0 bg-white/70 dark:bg-gray-900/70 rounded-xl"></div>

            <div class="relative">
                <svg
                    class="w-10 h-10 animate-spin text-black dark:text-white"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <defs>
                        {{-- Definimos el gradiente para la "cola" del spinner --}}
                        <linearGradient id="spinner-mix-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="currentColor" stop-opacity="0" />
                            <stop offset="100%" stop-color="currentColor" stop-opacity="1" />
                        </linearGradient>
                    </defs>

                    {{-- Fondo: Anillo segmentado (tech) sutil --}}
                    <circle
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-dasharray="3 5" {{-- Guiones cortos y espacios --}}
                        class="opacity-20"
                    />

                    {{-- Frente: Arco suave con gradiente --}}
                    {{-- Usamos un path para tener control total del inicio y fin del arco --}}
                    <path
                        d="M12 2 A10 10 0 0 1 21.5 9.5"
                        stroke="url(#spinner-mix-gradient)"
                        stroke-width="2.5"
                        stroke-linecap="round"
                    />
                </svg>
            </div>
        </div>
    @endif

</div>
