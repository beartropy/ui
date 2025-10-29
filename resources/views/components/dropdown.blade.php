@props([
    'side'         => 'bottom',         // 'bottom' | 'top'
    'placement'    => 'left',           // 'left' | 'center' | 'right'
    'usePortal'    => true,             // true => render fijo con teleport al <body>

    // Props parametrizables
    'autoFit'      => true,             // bool: solo se usa si maxHeight != null
    'autoFlip'     => true,             // bool: flip prudente top/bottom
    'maxHeight'    => null,             // null => NUNCA overflow/scroll
    'overflowMode' => 'auto',           // 'auto' | 'scroll' | 'visible' (si maxHeight != null)
    'flipAt'       => 96,               // umbral para flip (px)
    'minPanel'     => 140,              // alto mínimo (px)
    'zIndex'       => 'z-[99999999]',   // clase tailwind para z-index
    'width'        => null,             // ej: 'min-w-[12rem]' | 'w-64'; si null, usa preset
])

@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('dropdown');
    $dropdownId = $attributes->get('id') ?? 'dropdown-' . uniqid();
    $widthClass = $width ?: ($sizePreset['dropdownWidth'] ?? 'min-w-[12rem]');
@endphp

<div class="relative {{ $attributes->get('class') }}">
    <div
        x-data="{ open: false }"
        class="relative inline-block"
        x-id="['dropdown-{{ $dropdownId }}']"
        @keydown.escape.window="open = false"
        @click.away="open = false"
        @bt-dropdown-close.window="open = false"
    >
        <!-- Trigger -->
        <div @click="open = !open" x-ref="trigger" aria-haspopup="true" :aria-expanded="open">
            {{ $trigger ?? '' }}
        </div>

        @if(!$usePortal)
            {{-- 🔙 Modo clásico: usa el base, no tocamos nada --}}
            <x-beartropy-ui::base.dropdown-base
                x-show="open"
                :autoFit="false"
                x-transition
                x-anchor="$refs.trigger"
                side="{{ $side }}"
                placement="{{ $placement }}"
                color="{{ $presetNames['color'] }}"
                role="menu"
                width="{{ $widthClass }}"
            >
                <div class="py-1">
                    {{ $slot }}
                </div>
            </x-beartropy-ui::base.dropdown-base>
        @else
            {{-- 🧲 Modo portal: panel fijo fuera de stacking contexts --}}
            <template x-teleport="body">
                <div
                    x-data="{
                        // ⚙️ Config inyectada (parametrizable)
                        autoFit: {{ $autoFit ? 'true' : 'false' }},
                        autoFlip: {{ $autoFlip ? 'true' : 'false' }},
                        maxHeight: {{ $maxHeight !== null ? (int)$maxHeight : 'null' }},
                        overflowMode: '{{ $overflowMode }}',
                        flipAt: {{ (int)$flipAt }},
                        minPanel: {{ (int)$minPanel }},
                        zIndex: '{{ $zIndex }}',
                        widthClass: '{{ $widthClass }}',

                        // 📌 Regla: solo hay overflow si hay maxHeight explícito
                        allowOverflow: {{ $maxHeight !== null ? 'true' : 'false' }},

                        // Estado runtime
                        sideLocal: '{{ $side === 'top' ? 'top' : 'bottom' }}',
                        hasOverflow: false,
                        maxStyle: '',
                        coords: { top: 0, left: 0, width: 0, height: 0 },
                        panelW: 0,

                        _measure() {
                            const a = $refs.trigger;
                            if (!a) return;
                            const r = a.getBoundingClientRect();
                            this.coords = { top: r.top, left: r.left, width: r.width, height: r.height };
                        },

                        _reposition() {
                            this._measure();
                            const el = $el;

                            const vh = window.innerHeight || document.documentElement.clientHeight;
                            const spaceBelow = vh - (this.coords.top + this.coords.height);
                            const spaceAbove = this.coords.top;
                            const margin = 16;

                            if (this.autoFlip) {
                                const shouldFlip =
                                    (spaceBelow < Math.max(this.flipAt, margin + this.minPanel)) &&
                                    (spaceAbove > spaceBelow);
                                this.sideLocal = shouldFlip ? 'top' : 'bottom';
                            }

                            if (!this.allowOverflow) {
                                this.maxStyle = '';
                                this.hasOverflow = false;
                            } else {
                                const ideal = this.maxHeight ?? 300;
                                const contentNeed = Math.min(ideal, el.scrollHeight || ideal);
                                const room  = this.sideLocal === 'bottom' ? spaceBelow : spaceAbove;
                                const maxH  = Math.max(this.minPanel, Math.min(contentNeed, room - margin));
                                this.maxStyle = `max-height:${maxH}px;`;
                                this.hasOverflow = el.scrollHeight > (el.clientHeight + 1);
                            }

                            this.panelW = el.getBoundingClientRect().width || 0;
                        },

                        _computeLeft() {
                            // base left según placement
                            let left = this.coords.left;
                            if ('{{ $placement }}' === 'center') {
                                left = this.coords.left + (this.coords.width / 2);
                            } else if ('{{ $placement }}' === 'right') {
                                left = this.coords.left + this.coords.width;
                            }

                            // clamp horizontal (evita cortar en extremos)
                            const vw = window.innerWidth || document.documentElement.clientWidth;
                            const gap = 8;
                            let finalLeft = left;
                            if ('{{ $placement }}' === 'center') {
                                finalLeft = left - (this.panelW / 2);
                            } else if ('{{ $placement }}' === 'right') {
                                finalLeft = left - this.panelW;
                            }
                            finalLeft = Math.max(gap, Math.min(finalLeft, vw - this.panelW - gap));

                            return { left: finalLeft, translate: 'translateX(0)' };
                        },

                        _computeTop() {
                            return this.sideLocal === 'top'
                                ? (this.coords.top) - 8
                                : (this.coords.top + this.coords.height) + 8;
                        },

                        _bindListeners() {
                            const cb = () => { if (this.$data.open) { this._reposition(); this.$nextTick(()=>this._reposition()); } };
                            window.addEventListener('resize', cb, { passive:true });
                            window.addEventListener('scroll', cb,  { passive:true });

                            if (this.allowOverflow) {
                                const ro = new ResizeObserver(() => {
                                    // Recalcular overflow si cambia el contenido
                                    this.hasOverflow = $el.scrollHeight > ($el.clientHeight + 1);
                                });
                                ro.observe($el);
                            }
                        }
                    }"
                    x-init="_bindListeners()"
                    x-effect="open && $nextTick(() => { _reposition(); $nextTick(()=>_reposition()); })"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    @click.outside="open = false"
                    :class="[
                        zIndex,
                        widthClass,
                        sideLocal === 'top' ? 'origin-bottom' : 'origin-top',

                        // Overflow: si no se permite, siempre visible
                        (!allowOverflow)
                            ? 'overflow-visible'
                            : ((overflowMode === 'auto')
                                ? (hasOverflow ? 'overflow-y-auto' : 'overflow-visible')
                                : (overflowMode === 'scroll' ? 'overflow-y-scroll' : 'overflow-visible')),

                        // Scrollbar fina solo si realmente hay overflow y está permitido
                        (allowOverflow && hasOverflow && (overflowMode === 'auto' || overflowMode === 'scroll'))
                            ? 'beartropy-thin-scrollbar'
                            : '',

                        // Estilos visuales
                        'pointer-events-auto rounded-lg isolate {{ $colorPreset['dropdown_bg'] }} {{ $colorPreset['dropdown_shadow'] }} {{ $colorPreset['dropdown_border'] ?? '' }}'
                    ]"
                    :style="(() => {
                        const top = _computeTop();
                        const { left, translate } = _computeLeft();
                        return `position:fixed; top:${top}px; left:${left}px; transform:${translate}; min-width:8rem; ${maxStyle}`;
                    })()"
                    role="menu"
                >
                    <div class="py-1">
                        {{ $slot }}
                    </div>
                </div>
            </template>
        @endif
    </div>
</div>
