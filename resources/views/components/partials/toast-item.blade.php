@php
    $isMobile = ($variant ?? 'desktop-right') === 'mobile';
    $isLeft = str_contains($variant ?? '', 'left');

    // Card styling
    $cardClass = $isMobile
        ? 'w-full flex items-start border border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-3 bg-white/95 dark:bg-gray-900/95 pointer-events-auto relative shadow-lg overflow-hidden backdrop-blur supports-[backdrop-filter]:bg-white/70 dark:supports-[backdrop-filter]:bg-gray-900/70'
        : 'flex items-start border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 bg-white/90 dark:bg-gray-900/90 pointer-events-auto relative shadow overflow-hidden';

    // Transitions (left-positioned desktops slide from left)
    $translateX = $isLeft ? '-translate-x-6' : 'translate-x-6';
    $enterTransition = $isMobile ? 'transition-all ease-out duration-300' : 'transition-all ease-in-out duration-500';
    $enterStart      = $isMobile ? 'opacity-0 translate-y-2 scale-95' : "opacity-0 scale-95 {$translateX}";
    $enterEnd        = $isMobile ? 'opacity-100 translate-y-0 scale-100' : 'opacity-100 scale-100 translate-x-0';
    $leaveTransition = $isMobile ? 'transition-all ease-in duration-200' : 'transition-all ease-in duration-300';
    $leaveStart      = $isMobile ? 'opacity-100 translate-y-0 scale-100' : 'opacity-100 scale-100 translate-x-0';
    $leaveEnd        = $isMobile ? 'opacity-0 translate-y-2 scale-95' : "opacity-0 scale-95 {$translateX}";

    // Color intensity (mobile = bolder)
    $iconColor     = $isMobile ? '500' : '400';
    $progressColor = $isMobile ? '500' : '400';

    // Single-line toast text
    $singleTextColor = $isMobile ? 'text-gray-700' : 'text-gray-600';
    $singlePr        = $isMobile ? 'pr-6' : 'pr-2';

    // Progress bar
    $progressHeight = $isMobile ? 'h-[2px]' : 'h-[1px]';
    $progressRadius = $isMobile ? 'rounded-b-2xl' : 'rounded-b-xl';
@endphp

<div
    x-data="{
        show: true,
        timer: null,
        start: null,
        remaining: toast.duration,
        isSticky: toast.duration <= 0,
        init() {
            window.bt_toast_timers = window.bt_toast_timers || {};
            if (window.bt_toast_timers[toast.id]) {
                clearTimeout(window.bt_toast_timers[toast.id]);
            }
            if (this.isSticky) {
                if (this.$refs.progress) this.$refs.progress.style.width = '0%';
                return;
            }
            if (this.$refs.progress) {
                this.$refs.progress.style.width = '100%';
                this.$nextTick(() => {
                    this.$refs.progress.style.transition = 'width ' + this.remaining + 'ms linear';
                    this.$refs.progress.style.width = '0%';
                });
            }
            this.start = Date.now();
            window.bt_toast_timers[toast.id] = setTimeout(() => {
                this.show = false;
                Alpine.store('toasts').remove(toast.id);
                delete window.bt_toast_timers[toast.id];
            }, this.remaining);
        },
        pause() {
            if (this.isSticky) return;
            clearTimeout(window.bt_toast_timers[toast.id]);
            delete window.bt_toast_timers[toast.id];
            if (this.start) {
                this.remaining -= Date.now() - this.start;
                this.start = null;
            }
            const w = window.getComputedStyle(this.$refs.progress).width;
            this.$refs.progress.style.transition = 'none';
            this.$refs.progress.style.width = w;
        },
        resume() {
            if (this.isSticky) return;
            if (this.remaining <= 0) {
                this.show = false;
                Alpine.store('toasts').remove(toast.id);
                return;
            }
            this.start = Date.now();
            this.$refs.progress.style.transition = 'width ' + this.remaining + 'ms linear';
            this.$refs.progress.style.width = '0%';
            window.bt_toast_timers[toast.id] = setTimeout(() => {
                this.show = false;
                Alpine.store('toasts').remove(toast.id);
                delete window.bt_toast_timers[toast.id];
            }, this.remaining);
        }
    }"
    x-show="show"
    @mouseenter="pause" @mouseleave="resume"
    x-transition:enter="{{ $enterTransition }}"
    x-transition:enter-start="{{ $enterStart }}"
    x-transition:enter-end="{{ $enterEnd }}"
    x-transition:leave="{{ $leaveTransition }}"
    x-transition:leave-start="{{ $leaveStart }}"
    x-transition:leave-end="{{ $leaveEnd }}"
    class="{{ $cardClass }}"
>
    {{-- Icon --}}
    <div class="mt-1 mr-3 text-xl shrink-0">
        <template x-if="toast.type === 'success'">
            <svg class="w-6 h-6 text-green-{{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <polyline points="9 12 12 15 17 10" fill="none" />
            </svg>
        </template>
        <template x-if="toast.type === 'error'">
            <svg class="w-6 h-6 text-red-{{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <line x1="9" y1="9" x2="15" y2="15" />
                <line x1="15" y1="9" x2="9" y2="15" />
            </svg>
        </template>
        <template x-if="toast.type === 'warning'">
            <svg class="w-6 h-6 text-yellow-{{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linejoin="round">
                <path d="M12 3L2 21h20L12 3z" />
                <line x1="12" y1="10" x2="12" y2="14" />
                <circle cx="12" cy="17" r="1" fill="currentColor" />
            </svg>
        </template>
        <template x-if="toast.type === 'info' || !toast.type">
            <svg class="w-6 h-6 text-blue-{{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <circle cx="12" cy="8" r="1" fill="currentColor" />
                <line x1="12" y1="11" x2="12" y2="16" />
            </svg>
        </template>
    </div>

    {{-- Content --}}
    <div class="flex-1 min-w-0 pr-5">
        <template x-if="toast.single">
            <div class="{{ $singleTextColor }} dark:text-gray-300 mt-1 font-medium" x-text="toast.title"></div>
        </template>
        <template x-if="!toast.single">
            <div>
                <div class="font-semibold text-gray-900 dark:text-gray-100" x-text="toast.title"></div>
                <div class="text-gray-600 dark:text-gray-300 text-[15px]" x-text="toast.message"></div>
                <template x-if="toast.action">
                    <button
                        @click="if (toast.actionUrl) { window.location.href = toast.actionUrl; } show = false; Alpine.store('toasts').remove(toast.id)"
                        class="mt-1.5 text-sm font-semibold underline underline-offset-2 text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white cursor-pointer"
                        x-text="toast.action">
                    </button>
                </template>
            </div>
        </template>
    </div>

    {{-- Close --}}
    <button
        @click="show = false; Alpine.store('toasts').remove(toast.id)"
        class="absolute right-2 top-2 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded transition"
        aria-label="{{ __('beartropy-ui::ui.close') }}"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
    </button>

    {{-- Progress bar --}}
    <div
        class="absolute left-0 bottom-0 {{ $progressRadius }} {{ $progressHeight }}"
        :class="{
            'bg-blue-{{ $progressColor }}': toast.type === 'info' || !toast.type,
            'bg-green-{{ $progressColor }}': toast.type === 'success',
            'bg-red-{{ $progressColor }}': toast.type === 'error',
            'bg-yellow-{{ $progressColor }}': toast.type === 'warning',
        }"
        x-ref="progress"
        x-show="toast.duration > 0"
        style="width: 100%;"
    ></div>
</div>
