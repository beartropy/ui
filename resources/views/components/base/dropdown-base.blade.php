{{-- resources/views/components/base/dropdown-base.blade.php --}}
@props([
    // Posicionamiento base (desktop)
    'placement' => 'left',      // left|center|right
    'side'      => 'bottom',    // bottom|top
    'width'     => '',          // ej: 'min-w-[12rem]' o 'w-64'
    'presetFor' => 'dropdown',

    // Desktop/tablet
    'autoFit'   => true,        // adapta alto al viewport
    'autoFlip'  => true,        // invierte top/bottom si no hay espacio
    'maxHeight' => null,        // altura ideal o máxima
    'overflow'  => null,        // null => auto según autoFit/maxHeight | 'visible'|'auto'|'scroll'

    // Mobile
    'mobileMode'          => 'center', // fullscreen | sheet | center | none
    'mobileBreakpoint'    => 768,          // px: <= breakpoint se considera mobile
    'mobileAutoFit'       => true,         // ajusta alto al contenido
    'mobileFullTriggerVh' => 90,           // % viewport para pasar a fullscreen (sólo si mobileMode='fullscreen')
    'mobileSheetMaxVh'    => 82,           // % viewport para altura máxima del sheet (sólo si mobileMode='sheet')
    'mobileCenterMaxVh' => 80,

    // Nombre del boolean del padre que controla visibilidad (ej: 'open' o 'isOpen')
    'bind' => 'open',

    'triggerLabel' => 'Opciones',

    'teleportMobile'  => true,

])

@php
    [$colorPreset, $sizePreset] = $getComponentPresets($presetFor ?? 'dropdown');

    $alignment = match($placement ?? 'left') {
        'right'  => 'right-0',
        'center' => 'left-1/2 -translate-x-1/2',
        default  => 'left-0',
    };

    $preferredSide = ($side ?? 'bottom') === 'top' ? 'top' : 'bottom';

    // Overflow (desktop)
    $resolvedOverflow = $overflow
        ?? ($autoFit ? 'auto' : ($maxHeight ? 'auto' : 'visible'));

    $overflowClass = match($resolvedOverflow) {
        'auto'   => 'overflow-y-auto',
        'scroll' => 'overflow-y-scroll',
        default  => 'overflow-visible',
    };

    $thinScrollbar = in_array($resolvedOverflow, ['auto','scroll'], true)
        ? 'beartropy-thin-scrollbar'
        : '';

    $initialStyle = (!$autoFit && $maxHeight) ? "max-height:{$maxHeight}px;" : '';

@endphp

<div
    x-data="{
        // No definimos '{{ $bind }}' para no pisar el del padre
        sideLocal: '{{ $preferredSide }}',
        maxStyle: '{{ $initialStyle }}',

        // Config runtime
        mobileModeLocal: '{{ $mobileMode }}',
        isMobile: window.innerWidth <= {{ (int)$mobileBreakpoint }},

        // Medidas mobile
        mobileHeight: null,
        mobileHeaderH: 0,

        _onResize() {
            this.isMobile = window.innerWidth <= {{ (int)$mobileBreakpoint }};
            if (this.isMobile && {{ $bind }} ) this._measureAndSizeMobile();
        },

        // --- Desktop: cálculos de posición/alto ---
        _reposition() {
            if (!{{ $autoFit ? 'true' : 'false' }}) return;
            if (this.isMobile && this.mobileModeLocal !== 'none') return;

            const anchor = $el.parentElement;
            if (!anchor) return;

            const rect = anchor.getBoundingClientRect();
            const vh   = window.innerHeight || document.documentElement.clientHeight;

            const spaceBelow = vh - rect.bottom;
            const spaceAbove = rect.top;
            const margin = 16;
            const ideal  = {{ $maxHeight ? (int)$maxHeight : 300 }};
            const need   = Math.min(ideal, $el.scrollHeight || ideal);

            if ({{ $autoFlip ? 'true' : 'false' }}) {
                if (this.sideLocal === 'bottom') {
                    this.sideLocal = (spaceBelow < 160 && spaceAbove > spaceBelow) ? 'top' : 'bottom';
                } else {
                    this.sideLocal = (spaceAbove < 160 && spaceBelow > spaceAbove) ? 'bottom' : 'top';
                }
            }

            const room = this.sideLocal === 'bottom' ? spaceBelow : spaceAbove;
            const maxH = Math.max(140, Math.min(need, room - margin));
            this.maxStyle = `max-height:${maxH}px;`;
        },

        // --- Mobile: cálculo de alto dinámico (center/sheet/fullscreen) ---
        _measureAndSizeMobile() {
            if (!this.isMobile || this.mobileModeLocal === 'none') return;
            if (!{{ $bind }}) return;

            const panel   = $el.querySelector('[data-dd-mobile-panel]');
            const header  = $el.querySelector('[data-dd-mobile-header]');
            const content = $el.querySelector('[data-dd-mobile-content]');
            if (!panel || !content) return;

            const vh = window.innerHeight || document.documentElement.clientHeight;
            const headerH = header ? header.getBoundingClientRect().height : 0;
            this.mobileHeaderH = headerH;

            // Altura requerida por el contenido (+ header)
            const desired = headerH + (content.scrollHeight || 0);

            let h;
            if ({{ $mobileAutoFit ? 'true' : 'false' }}) {
                if (this.mobileModeLocal === 'fullscreen') {
                    const triggerPx = Math.round(vh * {{ (int)$mobileFullTriggerVh }} / 100);
                    h = desired >= triggerPx ? vh : Math.min(desired, vh);
                } else if (this.mobileModeLocal === 'sheet') {
                    const maxPx = Math.round(vh * {{ (int)$mobileSheetMaxVh }} / 100);
                    h = Math.min(desired, maxPx);
                } else { // center
                    const maxPx = Math.round(vh * {{ (int)$mobileCenterMaxVh }} / 100);
                    h = Math.min(desired, maxPx);
                }
            } else {
                h = (this.mobileModeLocal === 'fullscreen')
                    ? vh
                    : (this.mobileModeLocal === 'sheet'
                        ? Math.round(vh * {{ (int)$mobileSheetMaxVh }} / 100)
                        : Math.round(vh * 0.90));
            }

            this.mobileHeight = h;

            // Bordes según modo/alto
            const isFull = h >= (vh - 1);
            panel.classList.toggle('rounded-none', this.mobileModeLocal === 'fullscreen' && isFull);
            panel.classList.toggle('rounded-t-2xl', this.mobileModeLocal === 'sheet');
            panel.classList.toggle('rounded-2xl', this.mobileModeLocal === 'center');
        },

        _bindListeners() {
            const onResize = () => {
                this._onResize();
                if (!this.isMobile && {{ $bind }}) this._reposition();
            };
            window.addEventListener('resize', onResize, { passive:true });

            const onScroll = () => {
                if (!this.isMobile && {{ $bind }}) this._reposition();
            };
            window.addEventListener('scroll', onScroll, { passive:true, capture:true });

            // Recalcular al abrir/cerrar
            this.$watch(() => {{ $bind }}, (v) => {
                if (v) {
                    this.isMobile ? this._measureAndSizeMobile() : this._reposition();
                }
            });
        },
    }"
    x-init="_bindListeners()"
    x-effect="!isMobile && ({{ $bind }}) && $nextTick(() => _reposition())"
    @keydown.escape.stop.prevent="{{ $bind }} = false"
>
    {{-- 💻 Desktop/Tablet: dropdown posicionado relativo al trigger --}}
    <template x-if="!isMobile || mobileModeLocal === 'none'">
        <div
            x-show="{{ $bind }}"
            x-collapse
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.outside="{{ $bind }} = false"
            role="menu"
            aria-orientation="vertical"
            tabindex="-1"
            class="absolute z-50 {{ $alignment }} {{ $width }}
                rounded-lg
                {{ $colorPreset['dropdown_border'] ?? '' }}
                {{ $colorPreset['dropdown_bg'] }}
                {{ $colorPreset['dropdown_shadow'] }}
                {{ $overflowClass }}
                {{ $thinScrollbar }}"
            :class="sideLocal === 'top' ? 'bottom-full mb-1 origin-bottom' : 'top-full mt-1 origin-top'"
            :style="`min-width:8rem; ${maxStyle}`"
        >
            {{ $slot }}
        </div>
    </template>

    {{-- 📱 Mobile: center/sheet/fullscreen con auto-fit al contenido --}}
    <template x-if="isMobile && mobileModeLocal !== 'none'">
        <template x-teleport="body">
        <div
            x-show="{{ $bind }}"
            data-dd-mobile-overlay
            class="fixed inset-0 z-[999] flex flex-col bg-black/35 backdrop-blur-[2px]"
            :class="mobileModeLocal === 'center' ? 'items-center' : ''"
            @click.self="{{ $bind }} = false"
            x-effect="$nextTick(()=>_measureAndSizeMobile())"
        >
            <div
                data-dd-mobile-panel
                class="bg-white dark:bg-gray-900 shadow-xl transition-[height,transform] duration-200 ease-out rounded-lg {{ $colorPreset['dropdown_border'] ?? '' }}"
                :class="mobileModeLocal === 'center' ? 'w-[92%] max-w-md mt-[15vh]' : 'w-full mt-auto'"
                :style="mobileHeight
                ? `height:${mobileHeight}px; max-height:calc(100vh - 8px);`
                : 'max-height:calc(100vh - 8px);'"
            >
                {{-- Header con botón cerrar --}}
                <div data-dd-mobile-header class="flex items-center justify-between p-1">
                    <span class="px-3 text-lg font-medium text-gray-700 dark:text-gray-300">
                        {{ $triggerLabel }}
                    </span>
                    <button
                        type="button"
                        @click="{{ $bind }} = false"
                        class="inline-flex items-center justify-center rounded-full p-2
                               text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200
                               hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none"
                        aria-label="Cerrar"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Contenido scrolleable si excede el alto disponible --}}
                <div
                    data-dd-mobile-content
                    class="px-3 pb-3 overflow-y-auto beartropy-thin-scrollbar"
                    {{-- :style="mobileHeight ? `max-height: calc(${mobileHeight}px - ${mobileHeaderH}px)` : ''" --}}
                    style="max-height: 50vh;"
                >
                    {{ $slot }}
                </div>
            </div>
        </div>
        </template>
    </template>
</div>
