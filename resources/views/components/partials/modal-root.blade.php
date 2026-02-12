<div x-data="{
    // Independent local state
    localOpen: false,

    init() {
        @if ($attributes->wire('model')->value()) // One-way sync from Livewire → Alpine
            this.$watch('$wire.{{ $attributes->wire('model')->value() }}', (value) => {
                // Only update if different and not caused by us
                if (this.localOpen !== value) {
                    this.localOpen = value;
                }
            });

            // Initial state
            this.$nextTick(() => {
                this.localOpen = this.$wire.{{ $attributes->wire('model')->value() }};
            }); @endif
    },

    close() {
        this.localOpen = false;
        @if ($attributes->wire('model')->value()) // Update Livewire only when the user closes the modal
            this.$wire.set('{{ $attributes->wire('model')->value() }}', false); @endif
    },

    openModal() {
        this.localOpen = true;
        @if ($attributes->wire('model')->value()) // Update Livewire only when the user opens the modal
            this.$wire.set('{{ $attributes->wire('model')->value() }}', true); @endif
    }
}" x-show="localOpen" x-cloak id="{{ $modalId }}" x-ref="{{ $modalId }}"
    class="fixed inset-0 {{ $zIndexClass }} flex {{ $centered ? 'items-center' : 'items-start' }} justify-center transition-all px-4 sm:px-0"
    x-on:keydown.escape.window="close()" x-on:{{ $eventToClose }}.window="close()"
    x-on:{{ $eventToOpen }}.window="openModal()"
    x-effect="if(localOpen) { document.documentElement.classList.add('overflow-hidden') } else { document.documentElement.classList.remove('overflow-hidden') }"
    {{ $attributes->whereDoesntStartWith('wire:model') }}>
    <!-- Overlay -->
    <div x-show="localOpen"
        class="absolute inset-0 w-full h-full bg-gray-500/75 dark:bg-black/75 {{ $blurClass }} transition-all {{ $zIndexClass }}"
        @if ($closeOnClickOutside) @click="close()" @endif
        x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <!-- Modal Container -->
    <div x-show="localOpen" @click.stop
        class="relative w-full {{ $widthClass }} mx-auto {{ $centered ? '' : 'mt-24 sm:mt-32' }} rounded-xl shadow-[0_8px_48px_0_rgba(0,0,0,0.18)] p-4 {{ $bgColor }} {{ $blurClass }} transition-all {{ $zIndexClass }} overflow-y-auto max-h-[80vh]"
        x-transition:enter="ease-[cubic-bezier(.4,0,.2,1)] duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-6"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-6">
        @if ($styled || $showCloseButton)
            <button type="button"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-100 transition"
                @click="close()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif

        @isset($title)
            @if ($styled)
                <div {{ $title->attributes->merge(['class' => $titleClass]) }}>{{ $title }}</div>
            @else
                {{ $title }}
            @endif
        @endisset

        <!-- Modal content -->
        @if ($styled)
            <div class="{{ $slotClass }}">{{ $slot }}</div>
        @else
            {{ $slot }}
        @endif

        @isset($footer)
            @if ($styled)
                <div {{ $footer->attributes->merge(['class' => $footerClass]) }}>{{ $footer }}</div>
            @else
                {{ $footer }}
            @endif
        @endisset
    </div>
</div>
