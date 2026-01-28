@php
    [$colorPreset, $sizePreset] = $getComponentPresets('slider');

    $sideClasses = $side === 'left' ? 'left-0 pr-10' : 'right-0 pl-10';
    $dirEnterStart = $side === 'left' ? '-translate-x-full' : 'translate-x-full';
    $dirLeaveEnd = $side === 'left' ? '-translate-x-full' : 'translate-x-full';

    $focusRing = $colorPreset['ring'] ?? 'focus:ring-indigo-500';
    $iconHover = $colorPreset['hover'] ?? 'hover:text-gray-500 dark:hover:text-gray-300';
@endphp

<div x-cloak x-data="{
    show: @if ($attributes->wire('model')->value()) @entangle($attributes->wire('model')) @else false @endif,
    sliderName: '{{ $name }}',
    init() {
        @if ($name)
            window.addEventListener('open-slider', (e) => {
                if (e.detail === this.sliderName) this.show = true;
            });
            window.addEventListener('close-slider', (e) => {
                if (e.detail === this.sliderName) this.show = false;
            });
            window.addEventListener('toggle-slider', (e) => {
                if (e.detail === this.sliderName) this.show = !this.show;
            });
        @endif
    }
}"
    x-modelable="show"
    x-on:keydown.escape.window="show = false"
    class="relative z-50"
    role="dialog" aria-modal="true" {{ $attributes->whereDoesntStartWith('wire:model') }}>
    @if ($backdrop)
        {{-- BACKDROP --}}
        <div x-show="show" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 {{ $blur ? 'backdrop-blur-sm' : '' }} transition-opacity"
            @if (!$static) x-on:click="show = false" @endif></div>
    @endif

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 {{ $sideClasses }} flex max-w-full">

                {{-- PANEL --}}
                <div x-show="show" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="{{ $dirEnterStart }}" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="{{ $dirLeaveEnd }}"
                    class="pointer-events-auto w-screen {{ $maxWidth }}">
                    <div class="flex h-full flex-col bg-gray-50 dark:bg-gray-900 shadow-xl">

                        {{-- HEADER --}}
                        <div
                            class="bg-gray-50 dark:bg-gray-900 {{ $headerPadding }} border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <h2 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100"
                                    id="slide-over-title">
                                    {{ $title ?? '' }}
                                </h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button"
                                        class="relative rounded-md bg-white dark:bg-gray-800 text-gray-400 dark:text-gray-400 {{ $iconHover }} focus:outline-none focus:ring-2 {{ $focusRing }} focus:ring-offset-2"
                                        x-on:click="show = false">
                                        <span class="sr-only">Close</span>
                                        @include('beartropy-ui-svg::beartropy-x-mark', [
                                            'class' => 'w-6 h-6 shrink-0',
                                        ])
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- BODY --}}
                        <div class="relative flex-1 overflow-y-auto dark:text-gray-300 p-4" x-ref="scrollContainer">
                            {{ $slot }}
                        </div>

                        {{-- FOOTER --}}
                        @if (isset($footer))
                            <div
                                class="flex flex-shrink-0 justify-end px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-10 gap-2">
                                {{ $footer }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
