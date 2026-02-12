@if (app()->environment($env))
    <div x-data="{
            expanded: localStorage.getItem('debug_breakpoints_expanded')
                ? localStorage.getItem('debug_breakpoints_expanded') === 'true'
                : {{ $expanded ? 'true' : 'false' }},
            width: window.innerWidth,
            toggle() {
                this.expanded = !this.expanded;
                localStorage.setItem('debug_breakpoints_expanded', this.expanded);
            }
        }"
        x-init="$watch('width', value => value)"
        @resize.window="width = window.innerWidth"
        class="fixed bottom-0 right-0 z-[100] m-2 flex flex-col items-end"
        style="display: none;"
        x-show="true" {{-- Ensures Alpine takes control and removes display:none --}}
    >

        {{-- Expanded bar --}}
        <div x-show="expanded"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            class="flex items-center gap-3 p-2 text-xs font-mono text-white bg-red-600 border border-red-400 rounded shadow-lg opacity-90"
        >
            {{-- Breakpoint indicator --}}
            <div class="font-bold">
                <span class="block sm:hidden">XS</span>
                <span class="hidden sm:block md:hidden">SM</span>
                <span class="hidden md:block lg:hidden">MD</span>
                <span class="hidden lg:block xl:hidden">LG</span>
                <span class="hidden xl:block 2xl:hidden">XL</span>
                <span class="hidden 2xl:block">2XL</span>
            </div>

            <div class="h-4 w-px bg-white/40"></div>

            {{-- Pixel width --}}
            <div class="flex items-baseline space-x-0.5">
                <span x-text="width" class="font-bold"></span>
                <span class="text-[10px] opacity-80">px</span>
            </div>

            <div class="h-4 w-px bg-white/40"></div>

            {{-- Minimize button --}}
            <button @click="toggle()"
                    class="p-0.5 transition-colors rounded hover:bg-red-800 focus:outline-none cursor-pointer"
                    aria-label="{{ __('beartropy-ui::ui.minimize') }}"
                    :aria-expanded="expanded.toString()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        {{-- Minimized floating button --}}
        <button x-show="!expanded"
                @click="toggle()"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-50"
                x-transition:enter-end="opacity-75 scale-100"
                class="flex items-center justify-center w-8 h-8 text-white transition-all bg-red-600 border border-red-400 rounded-full shadow-lg opacity-50 hover:opacity-100 hover:scale-110 cursor-pointer"
                aria-label="{{ __('beartropy-ui::ui.show_debug_info') }}"
                :aria-expanded="expanded.toString()">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
            </svg>
        </button>

    </div>
@endif
