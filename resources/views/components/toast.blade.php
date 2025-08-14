@props([
    'position' => 'top-right',
])

<section
    x-data="{
        positions: {
            'top-right': 'top-6 right-6',
            'top-left': 'top-6 left-6',
            'bottom-right': 'bottom-6 right-6',
            'bottom-left': 'bottom-6 left-6'
        }
    }"
    x-init="
        if (!window.Alpine.store('toasts')) {
            Alpine.store('toasts', {
                items: [],
                add(toast) {
                    toast.id = toast.id || (
                        window.crypto && window.crypto.randomUUID
                            ? window.crypto.randomUUID()
                            : 'toast-' + Math.random().toString(36).slice(2) + Date.now()
                    );
                    toast.duration = toast.duration || 4000;
                    toast.position = toast.position || '{{$position}}';
                    this.items.push(toast);
                },
                remove(id) {
                    this.items = this.items.filter(t => t.id !== id);
                },
                grouped() {
                    return this.items.reduce((acc, toast) => {
                        let pos = toast.position || '{{$position}}';
                        acc[pos] = acc[pos] || [];
                        acc[pos].push(toast);
                        return acc;
                    }, {});
                },
                get(id) {
                    return this.items.find(t => t.id === id) || null;
                }

            });
            Livewire.on('beartropy-add-toast', t => {
                if (Array.isArray(t)) t = t[0];
                Alpine.store('toasts').add(t);
            });
        }
    "
>
    <template x-for="[pos, toasts] in Object.entries($store.toasts.grouped())" :key="pos">
        <div
            class="fixed z-50 flex flex-col gap-3 max-w-sm w-96"
            :class="positions[pos]"
            x-show="toasts && toasts.length"
            x-init=""
        >
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    x-data="{
                        show: true,
                        timer: null,
                        start: null,
                        remaining: toast.duration,
                        pause() {
                            clearTimeout(this.timer);
                            if (this.start) this.remaining -= Date.now() - this.start;
                            $refs.progress.style.transition = 'none';
                        },
                        resume() {
                            if (this.remaining <= 0) {
                                this.show = false;
                                Alpine.store('toasts').remove(toast.id);
                                return;
                            }
                            this.start = Date.now();
                            $refs.progress.style.transition = 'width ' + this.remaining + 'ms linear';
                            $refs.progress.style.width = '0%';
                            this.timer = setTimeout(() => {
                                this.show = false;
                                Alpine.store('toasts').remove(toast.id);
                            }, this.remaining);
                        }
                    }"
                    x-init="
                        $refs.progress.style.width = '100%';
                        $nextTick(() => {
                            $refs.progress.style.transition = 'width ' + toast.duration + 'ms linear';
                            $refs.progress.style.width = '0%';
                        });
                        start = Date.now();
                        timer = setTimeout(() => {
                            show = false;
                            Alpine.store('toasts').remove(toast.id);
                        }, toast.duration);
                    "
                    x-show="show"
                    @mouseenter="pause"
                    @mouseleave="resume"
                    x-transition:enter="transition-all ease-in-out duration-500"
                    x-transition:enter-start="opacity-0 scale-95 translate-x-6"
                    x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                    x-transition:leave="transition-all ease-in duration-300"
                    x-transition:leave-start="opacity-100 scale-100 translate-x-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-x-6"
                    class="flex items-start border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 bg-white/90 dark:bg-gray-900/90 pointer-events-auto relative shadow overflow-hidden"
{{--                     :class="{
                        'border-blue-400 ': toast.type === 'info',
                        'border-green-400': toast.type === 'success',
                        'border-red-400': toast.type === 'error',
                        'border-yellow-400': toast.type === 'warning',
                    }" --}}
                >
                    <!-- Icono SVG -->
                    <div class="mt-1 mr-3 text-xl shrink-0">
                        <template x-if="toast.type === 'success'">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" />
                                <polyline points="9 12 12 15 17 10" stroke="currentColor" stroke-width="2" fill="none"/>
                            </svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" />
                                <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" />
                                <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" />
                            </svg>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" />
                                <line x1="12" y1="8" x2="12" y2="13" stroke="currentColor" />
                                <circle cx="12" cy="16" r="1" fill="currentColor" />
                            </svg>
                        </template>
                        <template x-if="toast.type === 'info' || !toast.type">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" />
                                <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" />
                                <circle cx="12" cy="16" r="1" fill="currentColor" />
                            </svg>
                        </template>
                    </div>
                    <!-- Contenido -->
                    <div class="flex-1 min-w-0">
                        <template x-if="toast.single">
                            <div class="text-gray-600 dark:text-gray-300 mt-1 font-medium" x-text="toast.title"></div>
                        </template>
                        <template x-if="!toast.single">
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100" x-text="toast.title"></div>
                                <div class="text-gray-600 dark:text-gray-300 text-[15px]" x-text="toast.message"></div>
                            </div>
                        </template>
                    </div>
                    <!-- Botón cerrar -->
                    <button
                        @click="show = false; Alpine.store('toasts').remove(toast.id)"
                        class="absolute right-2 top-2 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded transition"
                        aria-label="Cerrar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" />
                            <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" />
                        </svg>
                    </button>
                    <!-- Barra de progreso inversa (ultra-fina) -->
                    <div
                        class="absolute left-0 bottom-0 rounded-b-xl"
                        style="height: 1px;"
                        :class="{
                            'bg-blue-400': toast.type === 'info',
                            'bg-green-400': toast.type === 'success',
                            'bg-red-400': toast.type === 'error',
                            'bg-yellow-400': toast.type === 'warning',
                        }"
                        x-ref="progress"
                        style="width: 100%;"
                    ></div>
                </div>
            </template>
        </div>
    </template>
</section>
