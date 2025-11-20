@php
    [$colorPreset, $sizePreset] = $getComponentPresets('card', null);

    $wrapperClasses = $colorPreset['wrapper']
        . ($noBorder ? ' border-0 shadow-none' : ' border border-gray-200 dark:border-gray-700');

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
</div>
