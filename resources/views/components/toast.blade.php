<section
    x-data="{
        positions: {
            'top-right': 'top-6 right-6',
            'top-left': 'top-6 left-6',
            'bottom-right': 'bottom-6 right-6',
            'bottom-left': 'bottom-6 left-6'
        },
        bottomOffsetPx: null,
        _resizeTimer: null,
        _resizeHandler: null,
        _bottomBarHandler: null,
        measureBottomBar() {
            const el = document.querySelector('[data-bottom-bar]');
            const h = el ? el.offsetHeight : 0;
            if (h > 0) {
                this.bottomOffsetPx = h;
                // Set global CSS var with safe-area
                document.documentElement.style.setProperty(
                    '--bt-bottom-offset',
                    `calc(${h}px + env(safe-area-inset-bottom))`
                );
            } else {
                this.bottomOffsetPx = null;
                // Remove so the prop fallback applies
                document.documentElement.style.removeProperty('--bt-bottom-offset');
            }
        },
        init() {
            if (!window.Alpine.store('toasts')) {
                Alpine.store('toasts', {
                    items: [],
                    add(toast) {
                        toast.id = toast.id || (
                            window.crypto?.randomUUID
                                ? window.crypto.randomUUID()
                                : 'toast-' + Math.random().toString(36).slice(2) + Date.now()
                        );

                        // Normalize duration: default 4000, 0 or negative means sticky
                        if (toast.duration === undefined || toast.duration === null) {
                            toast.duration = 4000;
                        } else {
                            toast.duration = Number(toast.duration);
                        }

                        toast.position = toast.position || '{{ $position }}';
                        this.items.push(toast);
                    },
                    remove(id) { this.items = this.items.filter(t => t.id !== id); },
                    grouped() {
                        return this.items.reduce((acc, toast) => {
                            const pos = toast.position || '{{ $position }}';
                            (acc[pos] ||= []).push(toast);
                            return acc;
                        }, {});
                    },
                    get(id) { return this.items.find(t => t.id === id) || null; }
                });
                Livewire.on('beartropy-add-toast', t => {
                    if (Array.isArray(t)) t = t[0];
                    Alpine.store('toasts').add(t);
                });
            }

            // Auto-detect bottom bar height
            this.measureBottomBar();
            this._resizeHandler = () => {
                clearTimeout(this._resizeTimer);
                this._resizeTimer = setTimeout(() => this.measureBottomBar(), 120);
            };
            this._bottomBarHandler = () => this.measureBottomBar();
            window.addEventListener('resize', this._resizeHandler);
            window.addEventListener('beartropy:bottom-bar:changed', this._bottomBarHandler);
        },
        destroy() {
            if (this._resizeHandler) {
                window.removeEventListener('resize', this._resizeHandler);
            }
            if (this._bottomBarHandler) {
                window.removeEventListener('beartropy:bottom-bar:changed', this._bottomBarHandler);
            }
        }
    }"
    {{-- Prop fallback with safe-area --}}
    style="--bt-prop-bottom-offset: calc({{ $bottomOffset }} + env(safe-area-inset-bottom));"
    role="status"
    aria-live="polite"
>
    {{-- Mobile (centered snackbar at bottom) --}}
    <template x-for="[pos, toasts] in Object.entries($store.toasts.grouped())" :key="'m-'+pos">
        <div
            class="md:hidden fixed z-[100] left-1/2 -translate-x-1/2 w-[min(92vw,28rem)] pb-[env(safe-area-inset-bottom)]"
            :style="{
                bottom: 'var(--bt-bottom-offset, var(--bt-prop-bottom-offset, calc(1rem + env(safe-area-inset-bottom))))'
            }"
            x-show="toasts && toasts.length"
        >
            <div class="flex flex-col gap-3 w-full">
                <template x-for="toast in toasts.slice(-{{ min($maxVisible, 3) }})" :key="toast.id">
                    @include('beartropy-ui::partials.toast-item', ['variant' => 'mobile'])
                </template>
            </div>
        </div>
    </template>

    {{-- Desktop (per-position containers with correct transition direction) --}}
    @foreach (['top-right' => 'right', 'top-left' => 'left', 'bottom-right' => 'right', 'bottom-left' => 'left'] as $pos => $side)
        <div
            class="hidden md:flex fixed z-[100] flex-col gap-3 max-w-sm w-96"
            :class="positions['{{ $pos }}']"
            x-show="$store.toasts.grouped()['{{ $pos }}']?.length > 0"
        >
            <template x-for="toast in ($store.toasts.grouped()['{{ $pos }}'] || []).slice(-{{ $maxVisible }})" :key="toast.id">
                @include('beartropy-ui::partials.toast-item', ['variant' => 'desktop-' . $side])
            </template>
        </div>
    @endforeach
</section>
