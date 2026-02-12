@props([
    'side'         => 'bottom',         // 'bottom' | 'top'
    'placement'    => 'left',           // 'left' | 'center' | 'right'
    'usePortal'    => true,             // true => fixed-position panel teleported to <body>

    // Props parametrizables
    'autoFit'      => true,             // bool: only used when maxHeight != null
    'autoFlip'     => true,             // bool: flip prudente top/bottom
    'maxHeight'    => null,             // null => never overflow/scroll
    'overflowMode' => 'auto',           // 'auto' | 'scroll' | 'visible' (si maxHeight != null)
    'flipAt'       => 96,               // threshold for flip (px)
    'minPanel'     => 140,              // minimum height (px)
    'zIndex'       => 'z-[99999999]',   // Tailwind class for z-index
    'width'        => null,             // e.g. 'min-w-[12rem]' | 'w-64'; if null, uses preset
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
            {{-- Classic mode: delegates to base component --}}
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
            {{-- Portal mode: fixed panel outside stacking contexts --}}
            <template x-teleport="body">
                <div
                    x-data="{
                        // Injected config (parameterizable)
                        autoFit: {{ $autoFit ? 'true' : 'false' }},
                        autoFlip: {{ $autoFlip ? 'true' : 'false' }},
                        maxHeight: {{ $maxHeight !== null ? (int)$maxHeight : 'null' }},
                        overflowMode: '{{ $overflowMode }}',
                        flipAt: {{ (int)$flipAt }},
                        minPanel: {{ (int)$minPanel }},
                        zIndex: '{{ $zIndex }}',
                        widthClass: '{{ $widthClass }}',

                        // Rule: overflow only when maxHeight is explicitly set
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
                                const preferred = '{{ $side === 'top' ? 'top' : 'bottom' }}';
                                const prefSpace = preferred === 'bottom' ? spaceBelow : spaceAbove;
                                const altSpace  = preferred === 'bottom' ? spaceAbove : spaceBelow;
                                const shouldFlip =
                                    (prefSpace < Math.max(this.flipAt, margin + this.minPanel)) &&
                                    (altSpace > prefSpace);
                                this.sideLocal = shouldFlip
                                    ? (preferred === 'bottom' ? 'top' : 'bottom')
                                    : preferred;
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
                            // base left from placement
                            let left = this.coords.left;
                            if ('{{ $placement }}' === 'center') {
                                left = this.coords.left + (this.coords.width / 2);
                            } else if ('{{ $placement }}' === 'right') {
                                left = this.coords.left + this.coords.width;
                            }

                            // clamp horizontal for left placement (prevent clipping at edges)
                            if ('{{ $placement }}' === 'left' && this.panelW > 0) {
                                const vw = window.innerWidth || document.documentElement.clientWidth;
                                const gap = 8;
                                left = Math.max(gap, Math.min(left, vw - this.panelW - gap));
                            }

                            return left;
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
                                    // Recalculate overflow when content changes
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

                        // Overflow: if not allowed, always visible
                        (!allowOverflow)
                            ? 'overflow-visible'
                            : ((overflowMode === 'auto')
                                ? (hasOverflow ? 'overflow-y-auto' : 'overflow-visible')
                                : (overflowMode === 'scroll' ? 'overflow-y-scroll' : 'overflow-visible')),

                        // Thin scrollbar only when overflow is present and allowed
                        (allowOverflow && hasOverflow && (overflowMode === 'auto' || overflowMode === 'scroll'))
                            ? 'beartropy-thin-scrollbar'
                            : '',

                        // Visual styles
                        'pointer-events-auto rounded-lg isolate {{ $colorPreset['dropdown_bg'] }} {{ $colorPreset['dropdown_shadow'] }} {{ $colorPreset['dropdown_border'] ?? '' }}'
                    ]"
                    :style="(() => {
                        const top = _computeTop();
                        const left = _computeLeft();
                        const tf = [];
                        if (sideLocal === 'top') tf.push('translateY(-100%)');
                        if ('{{ $placement }}' === 'center') tf.push('translateX(-50%)');
                        else if ('{{ $placement }}' === 'right') tf.push('translateX(-100%)');
                        return `position:fixed; top:${top}px; left:${left}px; transform:${tf.join(' ') || 'none'}; min-width:8rem; ${maxStyle}`;
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
