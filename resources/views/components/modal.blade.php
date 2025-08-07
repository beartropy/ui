@props([
    'id' => null,
    'maxWidth' => '3xl',
    'zIndex' => '10',
    'blur' => 'none',
    'bgColor' => 'bg-white dark:bg-gray-900',
    'closeOnClickOutside' => true,
    'styled' => false,
    'showCloseButton' => true,
])

@php

    $wrapperClass = $styled ? 'p-6' : '';
    $titleClass = $styled ? 'text-xl font-semibold border-b border-gray-200 dark:border-gray-700 pb-3 text-gray-800 dark:text-gray-200' : '';
    $slotClass = $styled ? 'my-4 text-gray-800 dark:text-gray-200' : '';
    $footerClass = $styled ? 'flex justify-end items-center border-t border-gray-200 dark:border-gray-700 pt-3' : '';


    $zIndex = 'z-' . $zIndex;
    $closeOnClickOutside = ${'close-on-click-outside'} ?? true;

    if ($attributes->wire('model')->value() !== false) {
        $modalId = $attributes->wire('model')->value();
    } else {
        $modalId = $id ?? 'modal-' . uniqid();
    }

    // Clases de tamaño
    $maxWidths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '6xl' => 'max-w-6xl',
        '7xl' => 'max-w-7xl',
        'full' => 'max-w-full',
    ];
    $widthClass = $maxWidths[$maxWidth] ?? 'max-w-3xl';

    // Clases de blur
    $blurLevels = [
        'none' => 'backdrop-blur-none',
        'sm' => 'backdrop-blur-sm',
        'md' => 'backdrop-blur-md',
        'lg' => 'backdrop-blur-lg',
        'xl' => 'backdrop-blur-xl',
        '2xl' => 'backdrop-blur-2xl',
        '3xl' => 'backdrop-blur-3xl',
    ];
    $blurClass = $blurLevels[$blur] ?? 'backdrop-blur-xl';

    // Evento personalizado para abrir modal Alpine
    $eventToOpen = 'open-modal-' . $modalId;
    $eventToClose = 'close-modal-' . $modalId;
@endphp

<div
    x-data="{
        @if ($attributes->wire('model')->value())
            open: @entangle($attributes->wire('model')),
        @else
            open: false,
        @endif
        close() { this.open = false; },
        openModal() { this.open = true; },
    }"
    x-show="open"
    x-cloak
    id="{{ $modalId }}"
    x-ref="{{ $modalId }}"
    class="fixed inset-0 {{ $zIndex }} flex items-start justify-center transition-all"
    x-on:keydown.escape.window="close()"
    x-on:{{ $eventToClose }}.window="close()"
    x-on:{{ $eventToOpen }}.window="openModal()"
>
    <!-- Overlay -->
    <div
        x-show="open"
        x-cloak
        class="absolute inset-0 w-full h-full bg-gray-500/75 dark:bg-black/75 {{ $blurClass }} transition-all {{ $zIndex }}"
        @if($closeOnClickOutside)
            @click="close()"
        @endif
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <!-- Modal -->
    <div
        x-show="open"
        x-cloak
        @click.stop
        {{ $attributes->merge(['class' => "relative mt-32 w-full $widthClass mx-auto rounded-xl shadow-[0_8px_48px_0_rgba(0,0,0,0.18)] p-4 $bgColor $blurClass transition-all $zIndex "]) }}
        x-transition:enter="ease-[cubic-bezier(.4,0,.2,1)] duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-6"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-6"
    >
        @if($styled || $showCloseButton)
            <!-- Botón cerrar -->
            <button type="button"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-100 transition"
                @click="close()"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        @endif

        @isset($title)
            @if($styled)
                <div class="{{ $titleClass }}">
                    {{ $title }}
                </div>
            @else
                {{ $title }}
            @endif
        @endisset

        @if($styled)
            <div class="{{ $slotClass }}">
                {{ $slot }}
            </div>
        @else
            {{ $slot }}
        @endif

        @isset($footer)
            @if($styled)
                <div class="{{ $footerClass }}">
                    {{ $footer }}
                </div>
            @else
                {{ $footer }}
            @endif
        @endisset
    </div>
</div>
