{{-- resources/views/components/base/dropdown-base.blade.php --}}
@props([
    // Base positioning (desktop)
    'placement' => 'left',      // left|center|right
    'side'      => 'bottom',    // bottom|top
    'width'     => '',          // e.g. 'min-w-[12rem]' or 'w-64'
    'presetFor' => 'dropdown',

    // Desktop/tablet
    'autoFit'   => true,        // adapts height to viewport
    'autoFlip'  => true,        // flips top/bottom if not enough space
    'maxHeight' => null,        // ideal or maximum height
    'overflow'  => null,        // null => auto based on autoFit/maxHeight | 'visible'|'auto'|'scroll'

    // Teleport: escapes overflow:hidden in modals/cards
    'teleport'  => true,       // true: renders in body with fixed position

    // Mobile
    'mobileMode'          => 'center', // fullscreen | sheet | center | none
    'mobileBreakpoint'    => 768,          // px: <= breakpoint se considera mobile
    'mobileAutoFit'       => true,         // adjusts height to content
    'mobileFullTriggerVh' => 90,           // % viewport to switch to fullscreen (only if mobileMode='fullscreen')
    'mobileSheetMaxVh'    => 82,           // % viewport for max sheet height (only if mobileMode='sheet')
    'mobileCenterMaxVh' => 80,

    // Name of the parent boolean that controls visibility (e.g. 'open' or 'isOpen')
    'bind' => 'open',

    'triggerLabel' => __('beartropy-ui::ui.options'),

    'teleportMobile'  => true,

    // Width behavior: true = match trigger width, false = min-width only (dropdown can be wider)
    'fitAnchor' => true,

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
        // We do not define '{{ $bind }}' here to avoid overriding the parent's value
        sideLocal: '{{ $preferredSide }}',
        maxStyle: '{{ $initialStyle }}',

        // Runtime config
        mobileModeLocal: '{{ $mobileMode }}',
        isMobile: window.innerWidth <= {{ (int)$mobileBreakpoint }},
        useTeleport: {{ $teleport ? 'true' : 'false' }},

        // Teleport positioning (fixed coordinates)
        teleportStyle: '',
        triggerRect: null,

        // Mobile measurements
        mobileHeight: null,
        mobileHeaderH: 0,

        _onResize() {
            this.isMobile = window.innerWidth <= {{ (int)$mobileBreakpoint }};
            if (this.isMobile && {{ $bind }} ) this._measureAndSizeMobile();
            if (!this.isMobile && this.useTeleport && {{ $bind }}) this._repositionTeleport();
        },

        // --- Desktop Teleport: fixed position relative to viewport ---
        _repositionTeleport() {
            if (!this.useTeleport || this.isMobile) return;
            if (!{{ $bind }}) return;

            // Find the trigger (the parent of the x-data div)
            const anchor = this.$root.parentElement;
            if (!anchor) return;

            const rect = anchor.getBoundingClientRect();
            this.triggerRect = rect;
            const vh = window.innerHeight || document.documentElement.clientHeight;
            const vw = window.innerWidth || document.documentElement.clientWidth;

            const spaceBelow = vh - rect.bottom;
            const spaceAbove = rect.top;
            const margin = 8;
            const ideal = {{ $maxHeight ? (int)$maxHeight : 300 }};

            // Auto-flip
            if ({{ $autoFlip ? 'true' : 'false' }}) {
                if (this.sideLocal === 'bottom') {
                    this.sideLocal = (spaceBelow < 160 && spaceAbove > spaceBelow) ? 'top' : 'bottom';
                } else {
                    this.sideLocal = (spaceAbove < 160 && spaceBelow > spaceAbove) ? 'bottom' : 'top';
                }
            }

            const room = this.sideLocal === 'bottom' ? spaceBelow : spaceAbove;
            const maxH = Math.max(140, Math.min(ideal, room - margin));

            // Calculate X position based on placement
            let leftPos = rect.left;
            const placement = '{{ $placement }}';
            if (placement === 'right') {
                leftPos = rect.right;
            } else if (placement === 'center') {
                leftPos = rect.left + (rect.width / 2);
            }

            // Calculate Y position
            let posStyle;
            if (this.sideLocal === 'bottom') {
                posStyle = `top:${rect.bottom + 4}px;`; // 4px gap below trigger
            } else {
                // Anchor bottom edge 4px above trigger top — dropdown grows upward
                posStyle = `bottom:${vh - rect.top + 4}px;`;
            }

            const widthRule = {{ $fitAnchor ? 'true' : 'false' }} ? `width:${rect.width}px` : `min-width:${rect.width}px`;
            this.teleportStyle = `position:fixed; ${posStyle} left:${leftPos}px; max-height:${maxH}px; ${widthRule}; z-index:9999;`;
            this.maxStyle = `max-height:${maxH}px;`;
        },

        // --- Desktop: position/height calculations (without teleport) ---
        _reposition() {
            if (this.useTeleport) {
                return this._repositionTeleport();
            }
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

        // --- Mobile: dynamic height calculation (center/sheet/fullscreen) ---
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

            // Height required by content (+ header)
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

            // Borders based on mode/height
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

            // Recalculate on open/close
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
    {{-- Desktop/Tablet WITHOUT teleport: dropdown positioned relative to trigger --}}
    <template x-if="(!isMobile || mobileModeLocal === 'none') && !useTeleport">
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

    {{-- Desktop/Tablet WITH teleport: dropdown in body with fixed position --}}
    <template x-if="(!isMobile || mobileModeLocal === 'none') && useTeleport">
        <template x-teleport="body">
            <div
                x-show="{{ $bind }}"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="{{ $bind }} = false"
                x-effect="{{ $bind }} && $nextTick(() => _repositionTeleport())"
                role="menu"
                aria-orientation="vertical"
                tabindex="-1"
                class="{{ $width }}
                    rounded-lg
                    {{ $colorPreset['dropdown_border'] ?? '' }}
                    {{ $colorPreset['dropdown_bg'] }}
                    {{ $colorPreset['dropdown_shadow'] }}
                    {{ $overflowClass }}
                    {{ $thinScrollbar }}"
                :class="{
                    'origin-top': sideLocal === 'bottom',
                    'origin-bottom': sideLocal === 'top',
                    '-translate-x-full': '{{ $placement }}' === 'right',
                    '-translate-x-1/2': '{{ $placement }}' === 'center'
                }"
                :style="teleportStyle"
            >
                {{ $slot }}
            </div>
        </template>
    </template>

    {{-- Mobile: center/sheet/fullscreen with auto-fit to content --}}
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
                {{-- Header with close button --}}
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
                        aria-label="{{ __('beartropy-ui::ui.close') }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Scrollable content when exceeding available height --}}
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
