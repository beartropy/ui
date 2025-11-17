@php
    [$colorPreset, $sizePreset] = $getComponentPresets('card', null);

    $wrapperClasses = $colorPreset['wrapper']
        . ($noBorder ? ' border-0 shadow-none' : '');

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
            class="{{ $colorPreset['title'] }} {{ $collapsable ? 'cursor-pointer select-none flex items-center justify-between gap-2' : '' }}"
            @if($collapsable)
                @click="open = !open"
                :class="open ? '' : ''"
            @endif
        >
            <div class="flex-1">
                {!! $title !!}
            </div>

            @if($collapsable)
                <span
                    class="inline-flex shrink-0 transition-transform duration-200"
                    :class="{ 'rotate-180': open }"
                >
                    {{-- Chevron down --}}
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

    <div
        class="{{ $colorPreset['slot'] }}"
        @if($collapsable)
            x-show="open"
            x-transition.duration.200ms
            x-collapse.duration.200ms
        @endif
    >
        <hr class="h-px my-2 bg-gray-300 dark:bg-gray-700 border-0">
        {!! $slot !!}
    </div>

    @if (!empty($footer))
        <div
            @if($collapsable)
                x-show="open"
                x-transition.duration.200ms
                x-collapse.duration.200ms
            @endif
        >
            <hr class="h-px my-2 bg-gray-300 dark:bg-gray-700 border-0">
            <div class="{{ $colorPreset['footer'] }}">
                {!! $footer !!}
            </div>
        </div>
    @endif
</div>
