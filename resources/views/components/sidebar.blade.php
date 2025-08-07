<aside
    x-data="{ mini: (window.localStorage.getItem('beartropy_ui_sidebar_mini') === 'true'), showTip: false, tipLabel: '', tipTop: 0, tipLeft: 0 }"
    :class="mini ? 'fixed top-0 left-0 h-screen w-20 z-40' : 'fixed top-0 left-0 h-screen w-64 z-40'"
    class="bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col shadow-sm transition-all duration-300 overflow-x-visible"
>
    <div class="h-20 flex items-center justify-center border-b border-gray-100 dark:border-gray-800">
        <img :class="mini ? 'h-8' : 'h-12'" src="/images/isotipo-transparent.png" alt="Logo">
    </div>
    <nav class="flex-1 overflow-y-auto px-2 py-4">
        <ul class="space-y-1">
            <li>
                <div class="relative group">
                    <a href="/" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-fuchsia-50 dark:hover:bg-gray-800 font-medium transition"
                        @mouseenter="
                            if(mini) {
                                let rect = $event.target.getBoundingClientRect();
                                showTip = true;
                                tipLabel = 'Inicio';
                                tipTop = rect.top + rect.height / 2 + window.scrollY;
                                tipLeft = rect.right + 10;
                            }
                        "
                        @mouseleave="showTip = false"
                        @focus="
                            if(mini) {
                                let rect = $event.target.getBoundingClientRect();
                                showTip = true;
                                tipLabel = 'Inicio';
                                tipTop = rect.top + rect.height / 2 + window.scrollY;
                                tipLeft = rect.right + 10;
                            }
                        "
                        @blur="showTip = false"
                        tabindex="0"
                        aria-label="Inicio"
                    >
                        <i class="fas fa-home text-xl"></i>
                        <span x-show="!mini" x-transition="">Inicio</span>
                    </a>
                </div>
            </li>
            <li>
                <div class="relative group">
                    <a href="/componentes" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-fuchsia-50 dark:hover:bg-gray-800 font-medium transition"
                        @mouseenter="
                            if(mini) {
                                let rect = $event.target.getBoundingClientRect();
                                showTip = true;
                                tipLabel = 'Componentes';
                                tipTop = rect.top + rect.height / 2 + window.scrollY;
                                tipLeft = rect.right + 10;
                            }
                        "
                        @mouseleave="showTip = false"
                        @focus="
                            if(mini) {
                                let rect = $event.target.getBoundingClientRect();
                                showTip = true;
                                tipLabel = 'Componentes';
                                tipTop = rect.top + rect.height / 2 + window.scrollY;
                                tipLeft = rect.right + 10;
                            }
                        "
                        @blur="showTip = false"
                        tabindex="0"
                        aria-label="Componentes"
                    >
                        <i class="fas fa-cube text-xl"></i>
                        <span x-show="!mini" x-transition="">Componentes</span>
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
        <div class="text-xs text-gray-400 text-center mt-2">v1.0.0 – Beartropy UI</div>
    </div>
    <button type="button"
        class="absolute top-1/4 right-0 translate-x-1/2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 shadow-md p-1 rounded-full border-2 border-white dark:border-gray-900 transition z-30"
        :class="{ 'rotate-180': mini }"
        @click="
            mini = !mini;
            window.localStorage.setItem('beartropy_ui_sidebar_mini', mini);
            window.dispatchEvent(new CustomEvent('toggle-mini', { detail: mini }));
            showTip = false;"
        :aria-label="mini ? 'Expandir menú' : 'Colapsar menú'"
        aria-label="Colapsar menú"
    >
        <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

    {{-- Tooltip fuera del sidebar en modo mini --}}
    <div
        x-show="mini && showTip"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed z-50 px-2 py-1 rounded bg-gray-900 text-white text-xs shadow-lg whitespace-nowrap pointer-events-none"
        :style="'top:' + tipTop + 'px; left:' + tipLeft + 'px; transform:translateY(-50%);'"
        x-cloak
        aria-hidden="true"
    >
        <span x-text="tipLabel"></span>
    </div>
</aside>
