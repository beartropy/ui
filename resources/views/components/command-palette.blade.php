@props([
    'items' => [],
    'src' => null,
    'cache' => true,
    'cacheKey' => 'bt-command-palette',
])

@php
    $id = $attributes->get('id') ?? 'bt-cp-' . uniqid();
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('command-palette');
@endphp

<div
    x-data="btCommandPalette({
        items: @js($items),
        src: @js($src),
        cache: {{ $cache ? 'true' : 'false' }},
        cacheKey: @js($cacheKey)
    })"
    @keydown.window.prevent.cmd.k="open = true"
    @keydown.window.prevent.ctrl.k="open = true"
    {{ $attributes->merge(['class' => 'relative']) }}
>
    {{-- Trigger --}}
    @if(trim($slot))
        <div @click="open = true">
            {{ $slot }}
        </div>
    @else
        <div @click="open = true">
            <x-beartropy-ui::input
                id="{{ $id }}"
                color="{{ $presetNames['color'] }}"
                size="{{ $presetNames['size'] ?? 'md' }}"
                icon-start="magnifying-glass"
                placeholder="Buscar en el sitio... (⌘ K / Ctrl K)"
                {{ $attributes->only(['fill', 'outline']) }}
            />
        </div>
    @endif

    {{-- Modal Overlay --}}
    <template x-teleport="body">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/40 backdrop-blur-xl z-[9999] flex items-start justify-center p-6"
            x-init="
                $watch('open', value => {
                    if (value) {
                        setTimeout(() => {
                            const el = document.getElementById('{{ $id }}-input');
                            if (el) el.focus();
                        }, 300);
                    }
                })
            "
        >
            <!-- Contenedor principal -->
            <div
                x-show="open"
                x-transition:enter="transition transform ease-[cubic-bezier(0.16,1,0.3,1)] duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition transform ease-[cubic-bezier(0.7,0,0.84,0)] duration-250"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-6"
                class="rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border border-gray-200/60 dark:border-gray-700/60 origin-center"
                :class="open ? '{{ $colorPreset['modal_bg'] ?? 'bg-white/80 dark:bg-gray-800/80' }}' : ''"
                @click.outside="open = false"
                @keydown.escape.window="open = false"
                @keydown.arrow-down.prevent="handleKey($event)"
                @keydown.arrow-up.prevent="handleKey($event)"
                @keydown.enter.prevent="handleKey($event)"
                @keydown.tab.prevent="handleKey($event)"
                @keydown.shift.tab.prevent="handleKey($event)"
                x-init="$watch('open', v => { if (v) selectedIndex = 0 })"
            >
                <!-- Input -->
                <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                    <x-beartropy-ui::input
                        id="{{ $id }}-input"
                        color="{{ $presetNames['color'] }}"
                        x-ref="search"
                        x-model="query"
                        placeholder="Buscar..."
                        icon-start="magnifying-glass"
                        autofocus
                    />
                </div>

                <!-- Resultados -->
                <ul class="max-h-96 overflow-y-auto divide-y divide-gray-200 dark:divide-gray-700 beartropy-thin-scrollbar">
                    <!-- Lista -->
                    <template x-if="filtered && filtered.length">
                        <template x-for="(item, index) in filtered" :key="item.action + '-' + index">
                            <li
                                :data-cp-index="index"
                                @click="execute(item)"
                                class="p-3 cursor-pointer transition-colors flex flex-col gap-1"
                                :class="{
                                    '{{ $colorPreset['hover_bg'] ?? 'hover:bg-gray-100 dark:hover:bg-gray-700' }}': true,
                                    'bg-beartropy-500/10 dark:bg-beartropy-400/20 ring-1 ring-beartropy-400/30': index === selectedIndex
                                }"
                            >
                                <!-- Encabezado -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium {{ $colorPreset['text'] }}" x-text="item.title"></span>
                                    </div>

                                    <!-- Tags -->
                                    <template x-if="item.tags && item.tags.length">
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="(tag, tindex) in item.tags" :key="item.action + '-tag-' + tindex">
                                                <span
                                                    @click.stop="query = tag"
                                                    class="text-[10px] px-2 py-0.5 rounded-full bg-gray-200/60 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-300/60 dark:hover:bg-gray-600/60 hover:text-gray-800 dark:hover:text-gray-200 cursor-pointer transition-colors"
                                                    x-text="tag"
                                                ></span>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                <!-- Descripción -->
                                <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1" x-text="item.description"></div>
                            </li>
                        </template>
                    </template>

                    <!-- Sin resultados -->
                    <template x-if="filtered && filtered.length === 0">
                        <li class="p-3 text-sm text-gray-400">Sin resultados</li>
                    </template>

                    <!-- Footer -->
                    <template x-if="!query && filtered && filtered.length === 5">
                        <li class="p-3 text-xs text-gray-400 text-center">
                            Mostrando los primeros 5 resultados
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </template>
</div>

@if(auth()->check())
    <script>
        window.__btUserPermissions = window.__btUserPermissions || @json(auth()->user()->getAllPermissions()->pluck('name'));
        window.__btUserRoles = window.__btUserRoles || @json(auth()->user()->getRoleNames());
    </script>
@endif
@if(class_exists(\Tighten\Ziggy\BladeRouteGenerator::class))
    {!! app(\Tighten\Ziggy\BladeRouteGenerator::class)->generate() !!}
@endif

<script>
function btCommandPalette({ items, src, cache, cacheKey }) {
    return {
        open: false,
        query: '',
        all: items || [],
        selectedIndex: 0,
        debounce: null,

        async init() {
            if (src) {
                const cached = localStorage.getItem(cacheKey);
                if (cache && cached) {
                    try {
                        const parsed = JSON.parse(cached);
                        if (Date.now() - parsed.timestamp < 86400000) {
                            this.all = parsed.data.filter(i => !i.permission || window.btCan(i.permission));
                        }
                    } catch {}
                }

                try {
                    const res = await fetch(src, { cache: 'no-store' });
                    const data = await res.json();
                    this.all = data.filter(i => !i.permission || window.btCan(i.permission));
                    if (cache) {
                        localStorage.setItem(cacheKey, JSON.stringify({ data, timestamp: Date.now() }));
                    }
                } catch (err) {
                    console.error('Error al cargar JSON del command palette:', err);
                }
            } else {
                this.all = this.all.filter(i => !i.permission || window.btCan(i.permission));
            }
        },

        get filtered() {
            const q = this.query?.toLowerCase().trim() || '';
            const allowed = this.all.filter(i => !i.permission || window.btCan(i.permission));

            if (!q) return allowed.slice(0, 5);

            const terms = q.split(/\s+/);
            const results = allowed.filter(i => {
                const text = [
                    i.title ?? '',
                    i.description ?? '',
                    Array.isArray(i.tags) ? i.tags.join(' ') : '',
                    i.action ?? '',
                    Array.isArray(i.permission) ? i.permission.join(' ') : (i.permission ?? '')
                ].join(' ').toLowerCase();
                return terms.every(t => text.includes(t));
            });

            if (results.length && this.selectedIndex >= results.length) this.selectedIndex = 0;
            return results;
        },

        scrollIntoView() {
            this.$nextTick(() => {
                const el = document.querySelector(`[data-cp-index="${this.selectedIndex}"]`);
                if (el) el.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            });
        },

        handleKey(e) {
            if (!this.filtered.length) return;

            if (['ArrowDown', 'Tab'].includes(e.key) && !e.shiftKey) {
                e.preventDefault();
                this.selectedIndex = (this.selectedIndex + 1) % this.filtered.length;
                this.scrollIntoView();
            } else if (['ArrowUp'].includes(e.key) || (e.key === 'Tab' && e.shiftKey)) {
                e.preventDefault();
                this.selectedIndex = (this.selectedIndex - 1 + this.filtered.length) % this.filtered.length;
                this.scrollIntoView();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const item = this.filtered[this.selectedIndex];
                if (item) this.execute(item);
            }
        },

        execute(item) {
            if (item.permission && !window.btCan(item.permission)) return;

            const action = item.action || '';
            const routes = this.routes || {};

            if (action.startsWith('route:')) {
                const name = action.replace('route:', '').trim();
                if (typeof window.route === 'function') window.location.href = route(name);
                else if (routes[name]) window.location.href = routes[name];
                else console.warn(`No se pudo resolver la ruta "${name}".`);
            } else if (action.startsWith('url:')) {
                window.location.href = action.replace('url:', '').trim();
            } else if (action.startsWith('emit:')) {
                window.livewire?.emit(action.replace('emit:', '').trim());
            } else if (action.startsWith('js:')) {
                try { eval(action.replace('js:', '')); } catch (e) { console.error(e); }
            }

            this.open = false;
        },
    }
}

// Helper global de permisos con soporte array (canany)
window.btCan = (permission) => {
    try {
        const userRoles = window.__btUserRoles || [];
        const userPerms = window.__btUserPermissions || [];
        if (userRoles.includes('admin')) return true;
        if (!userPerms.length) return true;
        if (Array.isArray(permission)) return permission.some(p => userPerms.includes(p));
        return userPerms.includes(permission);
    } catch {
        return true;
    }
};
</script>
